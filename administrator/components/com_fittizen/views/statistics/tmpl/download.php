<?php

use Dompdf\Dompdf;
require_once BASE_DIR.LIBS."dompdf".DS."dompdf_config.inc.php";

// Set some content to print
$html = '&lt;html&gt;
&lt;head&gt;
&lt;style&gt;

/* Type some style rules here */

&lt;/style&gt;
&lt;/head&gt;

&lt;body&gt;
<h3>Test</h3>
&lt;!-- Type some HTML here --&gt;

&lt;/body&gt;
&lt;/html&gt;';
$dompdf = new Dompdf();

$local = array("::1", "127.0.0.1");
$is_local = in_array($_SERVER['REMOTE_ADDR'], $local);

if ($is_local ) 
{
    if ( get_magic_quotes_gpc() )
    {
        $html = stripslashes($html);
    }

  $dompdf->set_paper('letter', 'portrait');
  $dompdf->load_html($html);
  $dompdf->render();
  $dompdf->stream("dompdf_out.pdf", array("Attachment" => false));
  exit(0);
}