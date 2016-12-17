<?php 
class RizoMailer { 

public $to,
     $from, $reply_to, $subject, $message, $content_type, $charset = "utf-8", $header = array(), $headerOutput, $successMSG = "Thank You! Your message has now been sent!", $errorMSG = "";
private $_debug = false; 
  




   public function __construct($args = array()){ 
     if (isset($args['isHTML']) && is_bool($args['isHTML'])){ 
       $this->isHTML($args['isHTML']); 
     } 
      if (isset($args['debug']) && is_bool($args['debug'])){ 
       $this->setDebug($args['debug']); 
     } 
     if (isset($args['to']) && self::isEmailValid($args['to'])){ 
       $this->setTo($args['to']); 
     } 
     if (isset($args['from']) && self::isEmailValid($args['from'])){ 
       $this->setFrom($args['from']); 
     } 
     if (isset($args['replyTo']) && self::isEmailValid($args['replyTo'])){ 
       $this->setReplyTo($args['replyTo']); 
     } 
     if (isset($args['subject'])){ 
       $this->setSubject($args['subject']); 
     } 
     if (isset($args['message'])){ 
       $this->setMessage($args['message']); 
     } 
     if (isset($args['charset'])){ 
       $this->setCharset($args['charset']); 
     } 
     if (isset($args['errorMsg'])){ 
       $this->setError($args['errorMsg']); 
     } 
     if (isset($args['successMsg'])){ 
       $this->setSuccess($args['successMsg']); 
     } 
  
   } 

   private function errorHandle($message){ 
     if($this->_debug === true): 
          print("<h1>MAIL ERROR</h1>"); 
          print("Description Of ERROR:<br />"); 
          printf("<span style='color:#FF0000; font-size:13pt;'>%s</span>",$message); 
          exit; 
          endif; 
   } 

  private function checkRequired(){ 
         if(!isset($this->to)) 
         $this->errorHandle("<strong>RizoMailer ERROR:</strong>You must provide a To Address for PHP to send any email"); 
  } 

   public function setDebug($debug){ 
       $this->_debug = $debug; 
       return $this; 
   } 

   public function isHTML($html = true){ 
       if($html === true): 
       $this->content_type = "text/html"; 
       else: 
       $this->content_type = "text/plain"; 
       endif; 
       return $this; 
   } 

   public static function isEmailValid($email){ 
      return filter_var($email,FILTER_VALIDATE_EMAIL) ? true : false; 
   } 
  
   public function setTo($to){ 
       $this->to = $to; 
       return $this; 
   } 
  
   public function setFrom($from){ 
       $this->from = $from; 
       return $this; 
   } 
  
    public function setReplyTo($reply_to){ 
       $this->reply_to = $reply_to; 
       return $this; 
   } 
   public function setSubject($subject){ 
       $this->subject = $subject; 
       return $this; 
   } 
  
    public function setMessage($message){ 
       $this->message = $message; 
       return $this; 
   } 
  
    public function setCharset($charset){ 
       $this->charset = $charset; 
       return $this; 
   } 
  
   public function setError($error){ 
       $this->errorMSG = $error; 
       return $this; 
   } 

    public function setSuccess($success){ 
       $this->successMSG = $success; 
       return $this; 
   } 

   private function _CreateHeaders(){ 
     $this->header[] = "From:".$this->from." <".$this->to.">\n"; 
     $this->header[] = "Reply-To:".$this->reply_to."\n"; 
     $this->header[] = "Content-Type:".$this->content_type.";charset=".$this->charset.""; 
      
     foreach($this->header as $this->headers){ 
         $this->headerOutput .= $this->headers; 
     } 
     return $this->headerOutput; 297      
   } 
  
   public function send(){  
    $this->_CreateHeaders(); 
    $this->checkRequired(); 

   if(!@mail($this->to,$this->subject,$this->message,$this->headerOutput)): 
    $this->errorHandle("<strong>ERROR:</strong>The php mail() function has failed to send your message!"); 
    else: 
    echo $this->_successMSG; 
    endif; 
   }   
} 
