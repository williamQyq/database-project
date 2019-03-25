<?php
    function translate_grade($var){
        switch ($var) {
            case "1":
                return "Freshman";       
                break;
            case "2":
                return "Sophmore";
                break;
            case "3":
                return "Junior";
                break;
            case "4":
                return "Senior";
                break;
            default:
                return "NAN";
        }
    }
?>