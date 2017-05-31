	

	<footer class="main-footer">
        <div class="pull-right hidden-xs">
          <b>QAM-OODP</b> Support Tool | Version 1.0
        </div>
        <strong>Copyright &copy; 2017 <a href="<?php echo base_url()."cUser/index"; ?>">QAM-OODP Support Tool</a>.</strong> All rights reserved.
    </footer>
    <script src="http://code.jquery.com/ui/1.11.2/jquery-ui.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>assets/dist/js/app.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>assets/js/jquery.validate.js" type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>assets/js/validation.js" type="text/javascript"></script>
    <script type="text/javascript">
        var windowURL = window.location.href;
        pageURL = windowURL.substring(0, windowURL.lastIndexOf('/'));
        var x= $('a[href="'+pageURL+'"]');
            x.addClass('active');
            x.parent().addClass('active');
        var y= $('a[href="'+windowURL+'"]');
            y.addClass('active');
            y.parent().addClass('active');
    </script>
	</body>
</html>