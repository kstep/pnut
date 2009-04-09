<?php
class Curl
{
	private $_curlh = null;
	private static $_curl_options = array(
		'autoReferer'       => CURLOPT_AUTOREFERER,
		'binaryTransfer'    => CURLOPT_BINARYTRANSFER,
		'cookieSession'     => CURLOPT_COOKIESESSION,
		'crlf'              => CURLOPT_CRLF,
		'dnsUseGlobalCache' => CURLOPT_DNS_USE_GLOBAL_CACHE,
		'failOnError'       => CURLOPT_FAILONERROR,
		'fileTime'          => CURLOPT_FILETIME,
		'followLocation'    => CURLOPT_FOLLOWLOCATION,
		'forbidReuse'       => CURLOPT_FORBID_REUSE,
		'freshConnect'      => CURLOPT_FRESH_CONNECT,
		'ftpUseEprt'        => CURLOPT_FTP_USE_EPRT,
		'ftpUseEpsv'        => CURLOPT_FTP_USE_EPSV,
		'ftpAppend'         => CURLOPT_FTPAPPEND,
		'ftpAscii'          => CURLOPT_FTPASCII,
		'ftpListOnly'       => CURLOPT_FTPLISTONLY,
		'header'            => CURLOPT_HEADER,
		'httpGet'           => CURLOPT_HTTPGET,
		'httpProxyTunnel'   => CURLOPT_HTTPPROXYTUNNEL,
		'mute'              => CURLOPT_MUTE,
		'netrc'             => CURLOPT_NETRC,
		'noBody'            => CURLOPT_NOBODY,
		'noProgress'        => CURLOPT_NOPROGRESS,
		'noSignal'          => CURLOPT_NOSIGNAL,
		'post'              => CURLOPT_POST,
		'put'               => CURLOPT_PUT,
		'returnTransfer'    => CURLOPT_RETURNTRANSFER,
		'sslVerifyPeer'     => CURLOPT_SSL_VERIFYPEER,
		'transferText'      => CURLOPT_TRANSFERTEXT,
		'unrestrictedAuth'  => CURLOPT_UNRESTRICTED_AUTH,
		'upload'            => CURLOPT_UPLOAD,
		'verbose'           => CURLOPT_VERBOSE,

		'bufferSize'        => CURLOPT_BUFFERSIZE,
		'closePolicy'       => CURLOPT_CLOSEPOLICY,
		'connectTimeout'    => CURLOPT_CONNECTTIMEOUT,
		'dnsCacheTimeout'   => CURLOPT_DNS_CACHE_TIMEOUT,
		'ftpSslAuth'        => CURLOPT_FTPSSLAUTH,
		'httpVersion'       => CURLOPT_HTTP_VERSION,
		'httpAuth'          => CURLOPT_HTTPAUTH,
		'inFileSize'        => CURLOPT_INFILESIZE,
		'lowSpeedLimit'     => CURLOPT_LOW_SPEED_LIMIT,
		'lowSpeedTime'      => CURLOPT_LOW_SPEED_TIME,
		'maxConnects'       => CURLOPT_MAXCONNECTS,
		'maxRedirs'         => CURLOPT_MAXREDIRS,
		'port'              => CURLOPT_PORT,
		'proxyAuth'         => CURLOPT_PROXYAUTH,
		'proxyPort'         => CURLOPT_PROXYPORT,
		'proxyType'         => CURLOPT_PROXYTYPE,
		'resumeFrom'        => CURLOPT_RESUME_FROM,
		'sslVerifyHost'     => CURLOPT_SSL_VERIFYHOST,
		'sslVersion'        => CURLOPT_SSLVERSION,

		'caInfo'            => CURLOPT_CAINFO,
		'caPath'            => CURLOPT_CAPATH,
		'cookie'            => CURLOPT_COOKIE,
		'cookieFile'        => CURLOPT_COOKIEFILE,
		'cookieJar'         => CURLOPT_COOKIEJAR,
		'customRequest'     => CURLOPT_CUSTOMREQUEST,
		'egbSocket'         => CURLOPT_EGBSOCKET,
		'encoding'          => CURLOPT_ENCODING,
		'ftpPort'           => CURLOPT_FTPPORT,
		'interface'         => CURLOPT_INTERFACE,
		'krb4Level'         => CURLOPT_KRB4LEVEL,
		'postFields'        => CURLOPT_POSTFIELDS,
		'proxy'             => CURLOPT_PROXY,
		'proxyUserPwd'      => CURLOPT_PROXYUSERPWD,
		'randomFile'        => CURLOPT_RANDOM_FILE,
		'range'             => CURLOPT_RANGE,
		'referer'           => CURLOPT_REFERER,
		'sslCipherList'     => CURLOPT_SSL_CIPHER_LIST,
		'sslCert'           => CURLOPT_SSLCERT,
		'sslCertPasswd'     => CURLOPT_SSLCERTPASSWD,
		'sslCertType'       => CURLOPT_SSLCERTTYPE,
		'sslEngine'         => CURLOPT_SSLENGINE,
		'sslEngineDefault'  => CURLOPT_SSLENGINE_DEFAULT,
		'sslKey'            => CURLOPT_SSLKEY,
		'sslKeyPasswd'      => CURLOPT_SSLKEYPASSWD,
		'sslKeyType'        => CURLOPT_SSLKEYTYPE,
		'url'               => CURLOPT_URL,
		'userAgent'         => CURLOPT_USERAGENT,
		'userPwd'           => CURLOPT_USERPWD,

		'http200Aliases'    => CURLOPT_HTTP200ALIASES,
		'httpHeader'        => CURLOPT_HTTPHEADER,
		'postQuote'         => CURLOPT_POSTQUOTE,
		'quote'             => CURLOPT_QUOTE,

		'file'              => CURLOPT_FILE,
		'inFile'            => CURLOPT_INFILE,
		'stdErr'            => CURLOPT_STDERR,
		'writeHeader'       => CURLOPT_WRITEHEADER,

		'headerFunction'    => CURLOPT_HEADERFUNCTION,
		'passwdFunction'    => CURLOPT_PASSWDFUNCTION,
		'readFunction'      => CURLOPT_READFUNCTION,
		'writeFunction'     => CURLOPT_WRITEFUNCTION,
	);

	private static $_curl_info = array(
		'effectiveUrl'          => CURLINFO_EFFECTIVE_URL,
		'httpCode'              => CURLINFO_HTTP_CODE,
		'fileTime'              => CURLINFO_FILETIME,
		'totalTime'             => CURLINFO_TOTAL_TIME,
		'nameLookupTime'        => CURLINFO_NAMELOOKUP_TIME,
		'connectTime'           => CURLINFO_CONNECT_TIME,
		'pretransferTime'       => CURLINFO_PRETRANSFER_TIME,
		'startTransferTime'     => CURLINFO_STARTTRANSFER_TIME,
		'redirectTime'          => CURLINFO_REDIRECT_TIME,
		'sizeUpload'            => CURLINFO_SIZE_UPLOAD,
		'sizeDownload'          => CURLINFO_SIZE_DOWNLOAD,
		'speedDownload'         => CURLINFO_SPEED_DOWNLOAD,
		'speedUpload'           => CURLINFO_SPEED_UPLOAD,
		'headerSize'            => CURLINFO_HEADER_SIZE,
		//'headerOut'           => CURLINFO_HEADER_OUT,
		'requestSize'           => CURLINFO_REQUEST_SIZE,
		'sslVerifyResult'       => CURLINFO_SSL_VERIFYRESULT,
		'contentLengthDownload' => CURLINFO_CONTENT_LENGTH_DOWNLOAD,
		'contentLengthUpload'   => CURLINFO_CONTENT_LENGTH_UPLOAD,
		'contentType'           => CURLINFO_CONTENT_TYPE,
	);

	private $_hard_exceptions = true;
	private $_error = "";
	private $_errno = "";

	public function __construct($url = null)
	{
		$this->_curlh = curl_init($url);
	}

	public function __destruct()
	{
		curl_close($this->_curlh);
	}

	public function __get($opt)
	{
		return curl_getinfo($this->_curlh, self::$_curl_info[$opt]);
	}

	public function __set($opt, $value)
	{
		if (!curl_setopt($this->_curlh, self::$_curl_options[$opt], $value))
			$this->throwException();
	}

	private function throwException()
	{
		if ($this->_hard_exceptions)
			throw new Exception(curl_error($this->_curlh), curl_errno($this->_curlh));
	}

	public function getErrorMessage()
	{
		return curl_error($this->_curlh);
	}

	public function getErrorCode()
	{
		return curl_errno($this->_curlh);
	}

	public function silent($value = true)
	{
		$this->_hard_exceptions = !$value;
		return $this;
	}

	public function run()
	{
		$result = curl_exec($this->_curlh);
		if ($result === false)
			$this->throwException();
		return $result;
	}

}
?>
