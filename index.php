<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" media="screen">

    <title>Business Dashboard App 1111</title>
</head>
<body>

<div class="navbar-header">
    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
    </button>
    <a class="navbar-brand" href="#">Brand</a>
</div>

<div class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">Business Dashboard App</a>
        </div>
        <div class="collapse navbar-collapse">
            <ul class="nav navbar-nav">
                <li class="active"><a href="#">Home</a></li>
                <li><a href="#about">About</a></li>
                <li><a href="#contact">Contact</a></li>
            </ul>
        </div><!--/.nav-collapse -->
    </div>
</div>

<br>
<br>

<div class="container">
    <h1>Register Here 1111</h1></br>
    <h4>Fill in your name and email address, then click <strong>Submit</strong> to register.</h4></br>

    <div class="row">
        <div class="col-lg-4">
            <form method="post" action="index.php" enctype="multipart/form-data">
                <input type="text" class="form-control" name="name" id="name" placeholder="Name" autofocus></br>
                <input type="text" class="form-control" name="email" id="email" placeholder="Email address"></br>
                <input type="submit" name="submit" value="Submit" button class="btn btn-lg btn-primary"/>
            </form>
        </div><!-- /col-lg-4 -->
    </div><!-- /row -->
</div><!-- /.container -->

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="//code.jquery.com/jquery.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="assets/js/bootstrap.min.js"></script>
</body>
</html>
