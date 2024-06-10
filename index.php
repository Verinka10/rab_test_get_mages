<?php
#
# @author Nikolai
# Get images from url
# https://phpsandbox.io/n/get-images-s9tvf#index.php
#

$count = 0;
$limit = 1000;
$totalSize = 0;
$result = [];

if (isset($_POST['url'])) {
    $url = $_POST['url'];
    if (filter_var($url, FILTER_VALIDATE_URL)) {
        $content = file_get_contents($_POST['url']);
        preg_match_all("/img src=\"([^\"]+)/", $content, $result);
    } 
}
?>
<html>
<body>
	<h2>Get images</h2>
	<hr>
		<form method="post">
			<input name="url" placeholder="url">
			<input type="submit" value="Го">
		</form>

		<table >
    	<?php foreach (array_chunk($result[1], 4) as $chunk) :?>
    		<tr>
    		<?php foreach ($chunk as $src) :?>
    		<?php
    		    if ($count == $limit) break;
                if (!$size = getSizse($url . $src)) continue;
                $count ++;
                $totalSize += $size; ?>
    			<td>
    				<a href="<?=$url.$src?>" target="_blank"> <img width="100" src="<?=$url.$src?>"></a>
    				<div><?= fmtSise($size); ?></div>
    			</td>
    		<?php endforeach; ?>
    		</tr>
    	 <?php endforeach; ?>
    	 <tfoot>
    		<tr><td>Total:<?= $count . ' / ' .fmtSise($totalSize) ?></td></tr>
      	  </tfoot>
		</table>
</body>
</html>

<?php 

function getSizse($url)
{
    $options = [
        'http' => [
            'method' => 'HEAD',
            'follow_location' => 0
        ]
    ];
    $size = null;
    $context = stream_context_create($options);
    @file_get_contents($url, NULL, $context);
    foreach ($http_response_header as $header) {
        if (stristr($header, 'Content-Length')) {
            $size = preg_replace("/\D+/", '', $header);
        }
    }
    return $size;
}


function fmtSise($size)
{
    return number_format($size / 1048576, 2) . ' MB';
}
