<?php namespace App\Utils;

class KeyGenerator implements KeyGeneratorInterface  {

        /**
         * Determine wether to use the open ssl module.
         *
         * @var boolean
	 * @access protected
         */
        protected $useOpenSsl;

        /**
         * Determine wether to use strong cryptography.
         *
         * @var boolean
	 * @access protected
         */
        protected $useStrongCrypto = true;

        /**
         * Constructor.
         */
        public function __construct()
        {
                // Determine whether to use OpenSSL
                if (!function_exists('openssl_random_pseudo_bytes'))
                {
                        $this->useOpenSsl = false;
                }
                else
                {
                        $this->useOpenSsl = true;
                }
        }

        /**
         * @inheritdoc
         */
        public function create($nbBytes = 64)
        {
                return rtrim(strtr(base64_encode($this->getRandomNumber($nbBytes)), '+/', '-_'), '=');
        }

        /**
         * Get a random number.
         *
         * @param string $nbBytes The number of bytes
         * @return integer
         */
        private function getRandomNumber($nbBytes)
        {
                // try OpenSSL
                if ($this->useOpenSsl)
                {
                        $bytes = openssl_random_pseudo_bytes($nbBytes, $this->useStrongCrypto);

                        if (false !== $bytes)
                        {
                                return $bytes;
                        }
                }

                return hash_hmac('sha1', str_shuffle(sha1(uniqid(mt_rand(), true), true)));
        }
}