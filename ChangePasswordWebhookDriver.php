<?php

class ChangePasswordWebhookDriver implements \RainLoop\Providers\ChangePassword\ChangePasswordInterface
{
	/**
	 * @var string
	 */
	private $sUrl = WebhookChangePasswordPlugin::DEFAULT_URL;

	/**
	 * @var string
	 */
	private $sAllowedEmails = '';

	/**
	 * @var \MailSo\Log\Logger
	 */
	private $oLogger = null;

	/**
	 * @param string $sUrl
	 *
	 * @return \ChangePasswordWebhookDriver
	 */
	public function SetUrl($sUrl)
	{
		$this->sUrl = $sUrl;
		return $this;
	}

	/**
	 * @param string $sAllowedEmails
	 *
	 * @return \ChangePasswordWebhookDriver
	 */
	public function SetAllowedEmails($sAllowedEmails)
	{
		$this->sAllowedEmails = $sAllowedEmails;
		return $this;
	}

	/**
	 * @param \RainLoop\Model\Account $oAccount
	 *
	 * @return bool
	 */
	public function PasswordChangePossibility($oAccount)
	{
		return $oAccount && $oAccount->Email() &&
		\RainLoop\Plugins\Helper::ValidateWildcardValues($oAccount->Email(), $this->sAllowedEmails);
	}

	/**
	 * @param \RainLoop\Model\Account $oAccount
	 * @param string $sPrevPassword
	 * @param string $sNewPassword
	 *
	 * @return bool
	 */
	public function ChangePassword(\RainLoop\Account $oAccount, $sPrevPassword, $sNewPassword)
	{
		$email = $oAccount->Email();
		$username = substr($email, 0, strpos($email, '@'));

		$post = [
			'username' 		 => $username,
			'old_password' => $sPrevPassword,
			'new_password' => $sNewPassword,
		];

		$ch = curl_init($this->sUrl);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post);

		$json = curl_exec($ch);
		curl_close($ch);

		$response = json_decode($json, true);
		if ($response['code'] === 200) {
			$bResult = true;
		} else {
			$bResult = false;
		}

		return $bResult;
	}
}