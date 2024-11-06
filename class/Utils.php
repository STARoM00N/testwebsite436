<?php

    class Bootstrap{
        public function DisplayAlert($message, $type){
            echo '<div class="alert alert-'. $type .'" role="alert">';
            echo ''. $message .'';
            echo '</div>';
        }
    }

?>