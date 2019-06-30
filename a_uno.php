<!DOCTYPE html> 
<html> 
	<head> 
	<title>Page Title</title> 
	
	<meta name="viewport" content="width=device-width, initial-scale=1"> 

	<link rel="stylesheet" href="http://code.jquery.com/mobile/1.1.0/jquery.mobile-1.1.0.min.css" />
	<script src="http://code.jquery.com/jquery-1.7.1.min.js"></script>
	<script src="http://code.jquery.com/mobile/1.1.0/jquery.mobile-1.1.0.min.js"></script>
</head> 

<body> 
<body> 

<!-- Start of first page -->
<div data-role="page" id="foo">

	<div data-role="header">
		<h1>Foo</h1>
	</div><!-- /header -->

	<div data-role="content">	
		<form action="form.php" method="post" data-ajax="false" enctype="multipart/form-data">
        <label for="basic">Text Input:</label>
<input type="text" name="name" id="basic" data-mini="true" />

        <input  type="file" name="x_identificacion_oficial_1" />
        
        
         </form>		
		<p>View internal page called <a href="#bar">bar</a></p>	
	</div><!-- /content -->
<ul data-role="listview" data-inset="true" data-filter="true" data-theme="r">
	<li><a href="#">Acura</a></li>
	<li><a href="#">Audi</a></li>
	<li><a href="#">BMW</a></li>
	<li><a href="#">Cadillac</a></li>
	<li><a href="#">Ferrari</a></li>
</ul>
	<div data-role="footer">
		<h4>Page Footer</h4>
	</div><!-- /footer -->
</div><!-- /page -->


<!-- Start of second page -->
<div data-role="page" id="bar">

	<div data-role="header">
		<h1>Bar</h1>
	</div><!-- /header -->

	<div data-role="content">	
		<p>I'm the second in the source order so I'm hidden when the page loads. I'm just shown if a link that references my ID is beeing clicked.</p>		
		<p><a href="#foo">Back to foo</a></p>	
	</div><!-- /content -->

	<div data-role="footer">
		<h4>Page Footer</h4>
	</div><!-- /footer -->
</div><!-- /page -->
</body>
</body>
</html>