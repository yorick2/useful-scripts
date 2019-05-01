<?php
$directory = 'templates';
$templateFiles=scandir($directory);
foreach($templateFiles as $template){
        if($template!='.' && $template!='..'){
                 echo '<br/>';
                echo '<a href="'.$directory.'/'.$template.'">'.$template.'</a>';
        }
}