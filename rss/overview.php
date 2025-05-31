<?php
require ( __DIR__  . '/../backend/php-setup.php');

try {
    $hashover = new \Hashover("json");
    $database = new \Hashover\Database($hashover->setup);

} catch (\Exception $error) {
    return;
}

// exit if no RSS token
if (!isset($hashover->setup->rssToken) || 
    $hashover->setup->rssToken === '' ||
    $_GET['t'] !== $hashover->setup->rssToken) {
    return;
}

// Set content type to RSS XML
header("Content-Type: text/xml; charset=UTF-8");

// Path to your SQLite database file
$dbFile = __DIR__ . '/../comments/hashover.sqlite'; // Change this to match your DB filename

try {
    $comments = $database->getRssOverview();
    // Output RSS XML

    $serverName = htmlspecialchars($_SERVER['SERVER_NAME']);
    $lastBuildDate = date('r');

    $commentsXML = '';
    foreach ($comments as $comment) {
        $pageURL =   htmlspecialchars($comment['page_url'] ?? '');
        $pageTitle = htmlspecialchars($comment['page_title'] ?? '');
        $commentBody = htmlspecialchars($comment['body'] ?? '');
        $pubDate = date('r', strtotime($comment['date']));
        $commentID = htmlspecialchars($comment['comment']);
        $guid = 'comment-' . $commentID ?? uniqid();
        $itemXML = <<<STOP
        <item>
            <title><![CDATA[New comment on $pageTitle]]></title>
            <description><![CDATA[<p>New comment on $pageTitle<p><p><a href="$pageURL#hashover-c$comment">$pageURL</a></p>
            <p>$commentBody</p>]]>
            </description>
            <pubDate>$pubDate</pubDate>
            <guid isPermaLink="false">$guid</guid>
        </item>
        STOP;
        $commentsXML .= $itemXML;
    }

    $rss = <<<ENDRSS
    <?xml version="1.0" encoding="UTF-8" ?>
    <rss version="2.0">
        <channel>
            <title>Hashover Recent Comments Feed</title>
            <link>$serverName</link>
            <description>The 15 most recent comments</description>
            <language>en-us</language>
            <lastBuildDate>$lastBuildDate</lastBuildDate>
            $commentsXML
        </channel>
    </rss>
    ENDRSS;
    echo $rss;
} catch (PDOException $e) {
    http_response_code(500);
    echo "Database error: " . htmlspecialchars($e->getMessage());
    exit;
}
