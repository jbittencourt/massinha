<?php
class templateForumAmadis extends templateAmadisNav {

    function templateForumAmadis() {
        global $proj, $url, $lang;

        $this->templateAmadisNav();
        $this->setMenuPrinc($lang[webfolio]);
    
    }
}