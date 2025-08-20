<?php
require ( __DIR__  . '/../backend/php-setup.php');

try {
    $hashover = new \Hashover("json");
    $overview = new \Hashover\RSSOverview($hashover->setup);

} catch (\Exception $error) {
    return;
}

// exit if no RSS token
if ($overview->checkRSSToken ($_GET['t']) === false) {
    return;
}

try {
    $comments = $overview->getRssOverview ();
    // Output RSS XML

    $serverName = htmlspecialchars ($_SERVER['SERVER_NAME']);

    // get first comment and use the date as the most recent build date
    $firstComment = reset($comments);

    $lastBuildDate = date('r', strtotime ($firstComment['date']));

    $commentsXML = '';
    foreach ($comments as $comment) {
        $pageURL =   htmlspecialchars ($comment['page_url'] ?? '');
        $pageTitle = htmlspecialchars ($comment['page_title'] ?? '');
        $commentBody = htmlspecialchars ($comment['body'] ?? '');
        $pubDate = date('r', strtotime ($comment['date']));
        $commentID = htmlspecialchars ($comment['comment']);
        $guid = "comment-$commentID";
        $itemXML = <<<STOP
        <item>
            <title><![CDATA[New comment on $pageTitle]]></title>
            <description><![CDATA[<p>New comment on $pageTitle<p><p><a href="$pageURL#hashover-c$commentID">$pageURL</a></p>
            <p>$commentBody</p>]]></description>
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
            <link>https://$serverName</link>
            <description>The 15 most recent comments</description>
            <language>en-us</language>
            <lastBuildDate>$lastBuildDate</lastBuildDate>
            $commentsXML
        </channel>
    </rss>
    ENDRSS;
    // header("Content-Type: application/rss+xml; charset=UTF-8");
    header("Content-Type: text/xml; charset=UTF-8");
    echo $rss;
} catch (PDOException $e) {
    http_response_code(500);
    echo "Error: " . htmlspecialchars ($e->getMessage());
    exit;
}
