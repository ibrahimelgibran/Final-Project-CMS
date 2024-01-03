<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<?php echo '<?xml version="1.0" encoding="' . $encoding . '"?>' . "\n"; ?>
<rss version="2.0"
    xmlns:dc="http://purl.org/dc/elements/1.1/"
    xmlns:sy="http://purl.org/rss/1.0/modules/syndication/"
    xmlns:admin="http://webns.net/mvcb/"
    xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
    xmlns:wfw="http://wellformedweb.org/CommentAPI/"
    xmlns:atom="http://www.w3.org/2005/Atom"
    xmlns:slash="http://purl.org/rss/1.0/modules/slash/"
    xmlns:georss="http://www.georss.org/georss"
    xmlns:geo="http://www.w3.org/2003/01/geo/wgs84_pos#"
    xmlns:media="http://search.yahoo.com/mrss/"
    xmlns:content="http://purl.org/rss/1.0/modules/content/">
    <channel>
    <title><?=$feed_name; ?></title>
    <link><?=$feed_url; ?></link>
    <description><?=$page_description; ?></description>
    <dc:language><?=$page_language; ?></dc:language>
    <dc:creator><?=$creator_email; ?></dc:creator>
    <dc:rights>Copyright <?=gmdate("Y", time()); ?></dc:rights>
    <admin:generatorAgent rdf:resource="https://www.sekolahku.web.id/" />
    <?php foreach($posts->result() as $row) { ?>
        <item>
            <title><?=xml_convert($row->post_title); ?></title>
            <link><?=site_url('read/' . $row->id.'/'.$row->post_slug)?></link>
            <guid><?=site_url('read/' . $row->id.'/'.$row->post_slug)?></guid>
            <description><![CDATA[ <?=character_limiter(strip_tags($row->post_content), 200); ?> ]]></description>
            <pubDate><?=$row->created_at; ?></pubDate>
        </item>
    <?php } ?>
    </channel>
</rss>