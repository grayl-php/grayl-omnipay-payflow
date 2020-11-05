<?php

   namespace Grayl\Omnipay\Payflow\Config;

   use Grayl\Omnipay\Common\Config\OmnipayAPIEndpointAbstract;

   /**
    * Class PayflowAPIEndpoint
    * The class of a single Payflow API endpoint
    *
    * @package Grayl\Omnipay\Payflow
    */
   class PayflowAPIEndpoint extends OmnipayAPIEndpointAbstract
   {

      /**
       * The username
       *
       * @var string
       */
      protected string $username;

      /**
       * The password
       *
       * @var string
       */
      protected string $password;

      /**
       * The partner
       *
       * @var string
       */
      protected string $partner;

      /**
       * The password
       *
       * @var string
       */
      protected string $vendor;


      /**
       * Class constructor
       *
       * @param string $api_endpoint_id The ID of this API endpoint (default, provision, etc.)
       * @param string $username
       * @param string $password
       * @param string $partner
       * @param string $vendor
       */
      public function __construct ( string $api_endpoint_id,
                                    string $username,
                                    string $password,
                                    string $partner,
                                    string $vendor )
      {

         // Call the parent constructor
         parent::__construct( $api_endpoint_id );

         // Set the class data
         $this->setUsername( $username );
         $this->setPassword( $password );
         $this->setPartner( $partner );
         $this->setVendor( $vendor );
      }


      /**
       * Gets the username
       *
       * @return string
       */
      public function getUsername (): string
      {

         // Return it
         return $this->username;
      }


      /**
       * Sets the username
       *
       * @param string $username The username to set
       */
      public function setUsername ( string $username ): void
      {

         // Set the username
         $this->username = $username;
      }


      /**
       * Gets the password
       *
       * @return string
       */
      public function getPassword (): string
      {

         // Return it
         return $this->password;
      }


      /**
       * Sets the password
       *
       * @param string $password The password to set
       */
      public function setPassword ( string $password ): void
      {

         // Set the password
         $this->password = $password;
      }


      /**
       * Gets the partner
       *
       * @return string
       */
      public function getPartner (): string
      {

         // Return it
         return $this->partner;
      }


      /**
       * Sets the partner
       *
       * @param string $partner The partner to set
       */
      public function setPartner ( string $partner ): void
      {

         // Set the partner
         $this->partner = $partner;
      }


      /**
       * Gets the vendor
       *
       * @return string
       */
      public function getVendor (): string
      {

         // Return it
         return $this->vendor;
      }


      /**
       * Sets the vendor
       *
       * @param string $vendor The vendor to set
       */
      public function setVendor ( string $vendor ): void
      {

         // Set the vendor
         $this->vendor = $vendor;
      }

   }