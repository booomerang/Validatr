<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>FORM</title>
</head>
<body>

<form action="post.php" method="post">
    <label for="name">Name</label>
    <input type="text" name="name" id="name" /><br/>

    <label for="password">Password</label>
    <input type="password" name="password" id="password" /><br/>

    <label for="email">Email</label>
    <input type="email" name="email" id="email" /><br/>

    <label for="checkbox">Checkbox</label>
    <input type="hidden" name="checkbox" value="0" />
    <input type="checkbox" name="checkbox" id="checkbox" value="1" /><br/>

    <input type="submit" />
</form>

</body>
</html>