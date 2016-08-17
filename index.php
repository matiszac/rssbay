<?php
    $domain = 'http://thepiratebay.org';
    $user = 'gurfel65';
    $url = 'http://thepiratebay.org/user/gurfel65/index.html';
    $content = file_get_contents($url);

    $magnets = explode( '<a href="magnet:?' , $content );
    $titles = explode( 'class="detLink" title="Details for ' , $content );
    $pages = explode( '<a href="/torrent/' , $content );

    // first part of rss
    $data =  '<rss xmlns:dc="http://purl.org/dc/elements/1.1/" version="2.0">';
    $data .= '<channel>';
    $data .= '<title>'.$domain.' - '.$user.'</title>';
    $data .= '<link>'.$domain.'</link>';
    $data .= '<description>Uploads by user: '.$user.'</description>';
    $data .= '<language>en</language>';
    $data .= '<pubDate></pubDate>';
    $data .= '<lastBuildDate></lastBuildDate>';
    $data .= '<docs>http://blogs.law.harvard.edu/tech/rss</docs>';
    $data .= '<generator>rssbay 0.1</generator>';

    // populate items
    $length = count($magnets);

    for ($i = 1; $i < $length; $i++) {
        // get titles
        $title = explode('">' , $titles[$i]);
        //get magnet links and replace & with &amp;
        $link = explode('" title=' , $magnets[$i] );
        $magnet = str_replace('&', '&amp;', $link[0]);
        //get page url
        $page = explode('/', $pages[$i]);

        $data .= '<item>';
        $data .= '<title><![CDATA[ '.$title[0].' ]]></title>';
        $data .= '<link>magnet:?'.$magnet.'</link>';
        $data .= '<comments>'.$domain.'/torrent/'.$page[0].'</comments>';
        $data .= '<pubDate></pubDate>';
        $data .= '<category domain="'.$domain.'/user/'.$user.'/"><![CDATA[ Uploads by user: '.$user.' ]]></category>';
        $data .= '<dc:creator><![CDATA[ '.$user.' ]]></dc:creator>';
        $data .= '<guid>'.$domain.'/torrent/'.$page[0].'</guid>';
        $data .= '<torrent xmlns="http://xmlns.ezrss.it/0.1/">';
        $data .= '<contentLength></contentLength>';
        $data .= '<infoHash></infoHash>';
        $data .= '<magnetURI><![CDATA[magnet:?'.$magnet.']]></magnetURI>';
        $data .= '</torrent>';
        $data .= '</item>';
    }

    // last part of rss
    $data .= '</channel>';
    $data .= '</rss>';

    header('Content-Type: application/xml');
    echo $data;


?>