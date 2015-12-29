<?php

class WebhookChangePasswordPlugin extends \RainLoop\Plugins\AbstractPlugin
{
	const DEFAULT_URL = 'https://127.0.0.1/change_password.php';

	public function Init()
	{
		$this->addHook('main.fabrica', 'MainFabrica');
	}

	/**
	 * @param string $sName
	 * @param mixed $oProvider
	 */
	public function MainFabrica($sName, &$oProvider)
	{
		switch ($sName)
		{
			case 'change-password':
				include_once __DIR__.'/ChangePasswordWebhookDriver.php';
				$oProvider = new ChangePasswordWebhookDriver();
				$oProvider
					->SetUrl($this->Config()->Get('plugin', 'change_password_url', ''))
					->SetAllowedEmails(\strtolower(\trim($this->Config()->Get('plugin', 'allowed_emails', ''))))
				;

				break;
		}
	}

	/**
	 * @return array
	 */
	public function configMapping()
	{
		return array(
			\RainLoop\Plugins\Property::NewInstance('change_password_url')->SetLabel('Url')
				->SetDefaultValue(WebhookChangePasswordPlugin::DEFAULT_URL)
				->SetDescription('URL for password change with POST request, required parameters: "username", "old_password", and "new_password"'),
			\RainLoop\Plugins\Property::NewInstance('allowed_emails')->SetLabel('Allowed emails')
				->SetType(\RainLoop\Enumerations\PluginPropertyType::STRING_TEXT)
				->SetDescription('Allowed emails, space as delimiter, wildcard supported. Example: user1@domain1.net user2@domain1.net *@domain2.net')
				->SetDefaultValue('*')
		);
	}
}
