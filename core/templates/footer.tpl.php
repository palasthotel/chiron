	<hr/>
	<p class="footer">A Project of the Palasthotel. <?php if(!is_numeric($_SESSION['hero'])){print 'You might want to <a href="?path=public/apply">apply</a> or <a href="?path=public/enter">enter</a>.';}else{print 'You are Hero No. '.$_SESSION['hero'].'. You might want to get your <a href="?path=private/lesson">todays lesson</a>. Or you might want to <a href="?path=public/leave">leave</a>.';}?></p>	
	</body>
</html>