<?php

        /**
         * This Method is to make sure image is ready to save in database
         */
        function readyToSave($image) {
            return  addslashes(file_get_contents($image['tmp_name'])); 
        }

        function readyToSavev2($image) {
            return file_get_contents($image);
        }
?>