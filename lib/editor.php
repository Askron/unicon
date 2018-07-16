<?
namespace Askron\Unicon;
use \Bitrix\Main\Loader;

\Bitrix\Main\Loader::includeModule('askron.unicon');

class Editor
{
	private const directives = array(
		'begin',
		'end'
	);

	private function putText($text) {
		$text = preg_replace('/ /g', '&nbsp;', $text);
		$text = preg_replace('/\t/g', '&nbsp;&nbsp;&nbsp;&nbsp;', $text);
		$text = preg_replace('/</g', '&lt;', $text);
		$text = preg_replace('/>/g', '&gt;', $text);
		$text = preg_replace('/\r\n/g', '<br>', $text);
		$text = preg_replace('/\n/g', '<br>', $text);
		$text = preg_replace('/\r/g', '<br>', $text);
		$('#text').html(text);
		return text;
	}

	private function deleteBreakFromBegin($text) {
		while ($text[0]==' ' || $text[0]=='\t' || $text[0]=='\n' || $text[0]=='\r') {
			$text = substring($text, 1, strlen($text));
		}
		return $text;
	}

	private function deleteBreakFromEnd($text) {
		while ($text[strlen($text)-1]==' ' || $text[strlen($text)-1]=='\t' || $text[strlen($text)-1]=='\n' || $text[strlen($text)-1]=='\r') {
			$text = substring($text, 0, strlen($text)-2);
		}
		return $text;
	}

	private function putPrologCheck($text, $position) {
		return substring($text, 0, $position) + '<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>\n' + substring($text, position);
	}

	private function changeTagHtml($text) {
		return preg_replace('/< *html[^>]*>/i', '<html xml:lang="<?=LANGUAGE_ID?>" lang="<?=LANGUAGE_ID?>">', $text);
	}

	private function putPanel($text) {
		return preg_replace($text, '/< *body[^>]*>/i', '$&\n\t<div id="panel"><?$APPLICATION->ShowPanel();?></div>', $text);
	}

	private function changeTagsImg($text) {
		return preg_replace_callback('/< *img[^>]*>/ig', function($str1){
			return preg_replace_callback('/src=("[^"]*"|\'[^\']*\')/i', function($str2){
				preg_match_all('/[/].+\.\w+ *[\"\']/i', $str2, $name);
				return preg_replace('/src=("[^"]*"|\'[^\']*\')/', 'src="<?=SITE_TEMPLATE_PATH?>/img' . $name, $text);
			}, $str1);
		}, $text);
	}

	private function buildHead($text) {
		$result = '<head>\n';
		$result .= '\t<title><?$APPLICATION->ShowTitle();?></title>\n';
		$result .= '\t<?$APPLICATION->ShowHead();?>\n';
		$result .= '\t<meta charset="<?=SITE_CHARSET?>"/>\n';
		$result .= '\t<meta name="viewport" content="width=device-width, initial-scale=1.0"/>\n';
		$result .= '\t<meta http-equiv="X-UA-Compatible" content="IE=edge"/>\n';
		$result .= '</head>\n';
		return $result;
	}

	public function buildHeader($text) {
		$directivePosition = strpos($text, '/\|begin\|/');
		if ($directivePosition!==false) {
			$text = substring($text, 1, $directivePosition);
			$text = self::deleteBreakFromEnd($text);
			$text = self::putPrologCheck($text, 0);
			$text = self::changeTagHtml($text);
			$text = preg_replace('/< *head[^>]*>[^]*< *\/ *head *>/ig', self::buildHead(text), $text);
			$text = self::putPanel($text);
			$text = self::changeTagsImg($text);
			return $text;
		} else {
			throw new analyzerError(1, 'Отсутсвует директива |begin|.');
		}
	}

	public function buildFooter($text) {
		$directivePosition = strpos($text, '/\|end\|/')+5;
		if ($directivePosition!==false) {
			$text = substring($text, $directivePosition);
			$text = self::deleteBreakFromBegin($text);
			$text = self::deleteBreakFromEnd($text);
			$text = self::putPrologCheck($text, 0);
			$text = self::changeTagsImg($text);
			return $text;
		} else {
			throw new analyzerError(2, 'Отсутсвует директива |end|.');
		}
	}

	public function parseText($text) {
	    try {
	    	foreach (self::directives as $val) {
	    		switch ($val) {
				case 'begin':
				    $result .= self::buildHeader($text);
				    break;
				case 'end':
					$result .= self::buildFooter($text);
				    break;
				}
	    	}
	    } catch (Exception $e) {
	        echo 'Возникла ошибка: ' . $e->getMessage(). "\n";
	    }
	    return true;
	}
}
?>