<?php
    function mongabay_menu_items() {

        //set up the list of names for menu items
        $items_en = array('Rainforests','Oceans','Animals','Environment','Business','Solutions','For Kids','DONATE','Impact','More');
        $items_es = array('Bosques','Océanos','Pueblos indígenas','	Investigaciones','Animales','Multimedia','Soluciones ','Entrevistas');
        $items_de = array('Regenwälder','Meere','Tiere und Umwelt','Für Kinder','Fotografie','WildTech','Mehr');
        $items_cn = array('简体字','繁体字');
        $items_jp = array('熱帯雨林','海洋','動物・環境','キッズ向け','写真','WildTech','その他');
        $items_it = array('Foreste Pluviali','Oceani','Animali & Ambiente','Per i bambini','WildTech','Altro');
        $items_brasil = array('Florestas Tropicais','Conservação','Meio Ambiente','Desmatamento','Povos Indígenas','Infraestrutura','Sobre');
        $items_fr = array('Forêts équatoriales','Océans','Animaux et environnement','Pour les enfants','Photographie','WildTech','Plus');
        $items_www = $items_en;
        $items_news = $items_en;
        $items_africa = $items_en;
        $items_srilanka = $items_en;
        $items_madagascar = $items_en;
        $items_philippines = $items_en;
        $items_india = array('Forests','Animals','Oceans','People','Rivers','Solutions','Opportunities','Mongabay Global','About');
        $items_wildtech = array('Mobile','Conservation Drones','Monitoring','Satellite imagery','Tracking','Human-wildlife Conflict','More');
        $items_kidsnews = array('Mongabay Kids','Lab','Library','Art & Craft','Puzzles & Games','Rainforests');
        $items_hindi = array('वन्य जीव एवं जैव विविधता','लोग','जलवायु परिवर्तन','प्राकृतिक संसाधन','ऊर्जा','खनन','समाधान','हमारे बारे में');
        $items_mongabaydev = $items_en; // TODO remove after going to prod
        $items_redesign = $items_en; // TODO remove after going to prod
        //set up arrays for menu item's links
        $url_base = esc_url( home_url( '/', 'http' ) );

        $urls_kidsnews = array('https://kids.mongabaydev.wpengine.com/','https://kids.mongabaydev.wpengine.com/sofias-lab/','https://kids.mongabaydev.wpengine.com/dougs-wild-library/','https://kids.mongabaydev.wpengine.com/sofias-lab/art-and-craft/','https://kids.mongabaydev.wpengine.com/sofias-lab/puzzles-and-games/','https://rainforests.mongabaydev.wpengine.com/kids/');
        $urls_en = array('https://rainforests.mongabaydev.wpengine.com/','https://news.mongabaydev.wpengine.com/list/oceans','https://news.mongabaydev.wpengine.com/list/wildlife','https://news.mongabaydev.wpengine.com/list/environment','https://news.mongabaydev.wpengine.com/list/business','https://news.mongabaydev.wpengine.com/list/conservation-solutions','https://kids.mongabaydev.wpengine.com/','https://donate.mongabay.org/?utm_source=mongabay.com&utm_medium=headerlink&utm_campaign=com-header-text-link-new','https://mongabay.org/topics/impacts/','https://www.mongabaydev.wpengine.com/about/',$url_base);
        $urls_es = array($url_base.'list/bosques',$url_base.'list/oceanos',$url_base.'list/pueblos-indigenas',$url_base.'list/investigaciones',$url_base.'list/animales',$url_base.'list/multimedia',$url_base.'list/soluciones',$url_base.'list/entrevistas/',$url_base);
        $urls_de = array($url_base.'list/regenwalder',$url_base.'list/meere',$url_base.'list/umwelt','http://global.mongabaydev.wpengine.com/de/','https://travel.mongabaydev.wpengine.com/','https://wildtech.mongabaydev.wpengine.com/',$url_base);
        $urls_cn = array('https://cn.mongabaydev.wpengine.com/list/%e7%ae%80%e4%bd%93%e5%ad%97/','https://cn.mongabaydev.wpengine.com/list/%e7%b9%81%e4%bd%93%e5%ad%97/');
        $urls_jp = array($url_base.'list/熱帯雨林',$url_base.'list/海洋',$url_base.'list/環境','http://global.mongabaydev.wpengine.com/jp/','https://travel.mongabaydev.wpengine.com/','https://wildtech.mongabaydev.wpengine.com/',$url_base);
        $urls_it = array($url_base.'list/rainforests',$url_base.'list/oceani',$url_base.'list/ambiente','http://global.mongabaydev.wpengine.com/it/','https://wildtech.mongabaydev.wpengine.com/',$url_base);
        $urls_india = array($url_base.'list/forests',$url_base.'list/animals', $url_base.'list/oceans',$url_base.'list/people',$url_base.'list/rivers',$url_base.'series/eco-hope/','https://www.mongabay.org/programs/news/opportunities/','https://news.mongabaydev.wpengine.com/',$url_base.'about/',$url_base);
        $urls_brasil = array($url_base.'list/florestas-tropicais/',$url_base.'list/conservacao/',$url_base.'list/ambiente/',$url_base.'list/desflorestacao/',$url_base.'list/povos-indigenas/',$url_base.'list/infraestrutura/',$url_base.'sobre/');
        $urls_fr = array($url_base.'list/forets-equatoriales',$url_base.'list/oceans',$url_base.'list/environnement','http://global.mongabaydev.wpengine.com/fr/','https://travel.mongabaydev.wpengine.com/','https://wildtech.mongabaydev.wpengine.com/',$url_base.'a-propos-de-mongabay/');
        $urls_hindi = array('https://hindi.mongabaydev.wpengine.com/list/wildlife-and-biodiversity/','https://hindi.mongabaydev.wpengine.com/list/people/','https://hindi.mongabaydev.wpengine.com/list/climate-change/','https://hindi.mongabaydev.wpengine.com/list/natural-resources/','https://hindi.mongabaydev.wpengine.com/list/solar-energy/','https://hindi.mongabaydev.wpengine.com/list/mining/','https://hindi.mongabaydev.wpengine.com/list/solutions/','https://hindi.mongabaydev.wpengine.com/about-us/',$url_base);
        $urls_www = array('https://rainforests.mongabaydev.wpengine.com/','https://news.mongabaydev.wpengine.com/list/oceans','https://news.mongabaydev.wpengine.com/list/wildlife','https://news.mongabaydev.wpengine.com/list/environment','https://news.mongabaydev.wpengine.com/list/business','https://news.mongabaydev.wpengine.com/list/conservation-solutions','https://kids.mongabaydev.wpengine.com/','https://donate.mongabay.org/?utm_source=mongabay.com&utm_medium=headerlink&utm_campaign=com-header-text-link-new','https://mongabay.org/topics/impacts/','https://www.mongabaydev.wpengine.com/about/',$url_base);
        $urls_news = $urls_en;
        $urls_africa = $urls_en;
        $urls_srilanka = $urls_en;
        $urls_madagascar = $urls_en;
        $urls_philippines = $urls_en;
        $urls_wildtech = array('https://news.mongabaydev.wpengine.com/list/mobile','https://news.mongabaydev.wpengine.com/list/conservation-drones','https://news.mongabaydev.wpengine.com/list/monitoring','http://news.mongabaydev.wpengine.com/list/satellite-imagery','http://news.mongabaydev.wpengine.com/list/tracking','https://news.mongabaydev.wpengine.com/list/human-wildlife-conflict','https://news.mongabaydev.wpengine.com/about-wildtech/',$url_base);
        $urls_mongabaydev = $urls_en;
        $urls_redesign = $urls_en; // TODO remove after going to prod
        //get current site host name
        $site = mongabay_subdomain_name();

        //return unordered list with menu items
        $count = 0;
        foreach (${"items_".$site} as $item) {
            $count = $count + 1;
            $item_url = ${"urls_".$site}[($count -1)];
            echo '<li class="nav-item '.$count.'">';
            echo '<a href="'.$item_url.'" class="nav-link">';
            echo $item;
            echo '</a>';
            echo '</li>';
        }
    }
?>
