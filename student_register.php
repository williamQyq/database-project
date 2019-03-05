<?php

?>
<html>
    <head>
        <title>Student Register</title>
    </head>
    <body>
        <h1> student register</h1>
        <form action="student_register_info.php" method="post">
           <table>
               <tr>
                    <td><input type="text" name="email" placeholder="enter your email address"/></td>
                </tr>
                <tr>
                    <td><input type="text" name="parentEmail" placeholder="enter your parent email"/></td>
                </tr>
                <tr>
                    <td><input type="text" name="password" placeholder="enter your password"/></td>
                </tr>
                <tr>
                    <td>Role:</td>    
                    <td>
                        <select name="role">
                        <option value="noRole">no role</option>
                        <option value="mentee">mentee</option>
                        <option value="mentor">mentor</option>
                        <option value="both">both</option>   
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Grade:</td>
                    <td>    
                    <select name="grade">
                        <option value="freshman">freshman</option>
                        <option value="sophmore">sophmore</option>
                        <option value="junior">junior</option>
                        <option value="senior">senior</option>
                    </select>
                    </td>
                </tr>
                <tr>
                    <td><input type="text" name="name" placeholder="enter your name"/></td>
                </tr>
                <tr>
                    <td><input type="tel" name="phoneNumber" placeholder="enter your phone number"/></td>
                </tr>
                <tr>
                    <td><input type="submit" value="submit"/></td>
                </tr>
            </table>
        </form>
    </body>
</html>