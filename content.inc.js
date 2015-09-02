var pop_screen_left = 0;
var pop_screen_top = 0;
var pop_screen_width = (screen.width);
var pop_screen_height = (screen.height*(2/3));
return window.open('includes/text_viewer.php?filePath=../' + element.path + element.name, 'Text Viewer', 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width=' + pop_screen_width + ', height=' + pop_screen_height + ', top=' + pop_screen_top + ', left=' + pop_screen_left);
