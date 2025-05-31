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
    echo "<?xml version=\"1.0\" encoding=\"UTF-8\" ?>\n";
    ?>
<rss version="2.0">
<channel>
    <title>Hashover Recent Comments Feed</title>
    <link><?php echo htmlspecialchars($_SERVER['SERVER_NAME']   ); ?></link>
    <description>The 15 most recent comments</description>
    <language>en-us</language>
    <lastBuildDate><?php echo date('r'); ?></lastBuildDate>
<?php foreach ($comments as $comment): 
    $page_url = htmlspecialchars($comment['page_url'] ?? '');
?>
    <item>
        <title><![CDATA[New comment on <?php echo htmlspecialchars($comment['page_title'] ?? ''); ?>]]></title><description><![CDATA[<p>New comment on <?php echo htmlspecialchars($comment['page_title'] ?? ''); ?><br /><?php echo '<a href="' . $page_url . '">' . $page_url . '</a>'; ?></p><?php echo htmlspecialchars($comment['body'] ?? ''); ?>]]></description><pubDate><?php echo date('r', strtotime($comment['date'])); ?></pubDate><guid isPermaLink="false"><?php echo 'comment-' . htmlspecialchars($comment['comment'] ?? uniqid()); ?></guid>s
    </item>
<?php endforeach; ?>
</channel>
</rss>
<?php

} catch (PDOException $e) {
    http_response_code(500);
    echo "Database error: " . htmlspecialchars($e->getMessage());
    exit;
}
