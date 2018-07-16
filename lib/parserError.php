<?
namespace Askron\Unicon;

class ParserError extends \Bitrix\Main\SystemException
{
	protected $parameters1;
	protected $parameters2;

	public function __construct($type='parsing error', $parameters1 = 0, $parameters2 = 0, \Exception $previous = null)
	{
		$message = 'An error has occurred: ' . $type;

		$this->parameters1 = $parameters1;
		$this->parameters2 = $parameters2;

		parent::__construct($message, false, false, false, $previous);
	}

	public function getParameters1()
	{
		return $this->parameters1;
	}

	public function getParameters2()
	{
		return $this->parameters2;
	}
}
?>