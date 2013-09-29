<?php

include_once("config.php");

if(loggedIn()):
    header('Location: members.php');
endif;

if(isset($_POST["submit"])):
    if(!($row = checkPass($_POST["username"], $_POST["password"]))):
        echo "<p>Incorrect login/password, try again</p>";
    else:
        cleanMemberSession($_POST["username"], $_POST["password"]);
        header("Location: members.php");
    endif;
endif;
?>
<html>
<head>
    <title>Simple Authentication with MongoDB</title>
</head>
<body>
<form action="<?=$_SERVER["PHP_SELF"];?>" method="POST">
    <table>
        <tr>
            <td>
                Username:
            </td>
            <td>
                <input type="text" name="username" value="<?php print isset($_POST["username"]) ? $_POST["username"] : "" ; ?>" maxlength="30">
            </td>
        </tr>
        <tr>
            <td>
                Password:
            </td>
            <td>
                <input type="password" name="password" value="" maxlength="30">
            </td>
        </tr>
        <tr>
            <td>
                &nbsp;
            </td>
            <td>
                <input name="submit" type="submit" value="Submit">
            </td>
        </tr>
    </table>
</form>
</body>
</html>