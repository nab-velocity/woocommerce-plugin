<?php
class VelocityError extends Exception
{
	public function __construct($message, $name) {
		parent::__construct($message);
		$this->name = $name;
	}
}

# 207
class VelocityCWSFault extends VelocityError
{
	public function __construct() { 
		parent::__construct('General CWS fault', 'CWSFault');
	}
}

# 208
class VelocityCWSInvalidOperationFault extends VelocityError
{
	public function __construct() { 
		parent::__construct('Invalid operation is being attempted', 'CWSInvalidOperationFault');
	}
}

# 225
class VelocityCWSValidationResultFault extends VelocityError
{
	public function __construct() { 
		parent::__construct('Xml validation Errors', 'CWSValidationResultFault');
	}
}

# 306
class VelocityCWSInvalidMessageFormatFault extends VelocityError
{
	public function __construct() { 
		parent::__construct('Invalid Message Format', 'CWSInvalidMessageFormatFault');
	}
}

# 312
class VelocityCWSDeserializationFault extends VelocityError
{
	public function __construct() { 
		parent::__construct('Deserialization not Successfull', 'CWSDeserializationFault');
	}
}

# 313
class VelocityCWSExtendedDataNotSupportedFault extends VelocityError
{
	public function __construct() { 
		parent::__construct('Manage Billing Data Not Supported', 'CWSExtendedDataNotSupportedFault');
	}
}

# 314
class VelocityCWSInvalidServiceConfigFault extends VelocityError
{
	public function __construct() { 
		parent::__construct('Invalid Service Configuration', 'CWSInvalidServiceConfigFault');
	}
}

# 317
class VelocityCWSOperationNotSupportedFault extends VelocityError
{
	public function __construct() { 
		parent::__construct('Operation Not Supported', 'CWSOperationNotSupportedFault');
	}
}

# 318
class VelocityCWSTransactionFailedFault extends VelocityError
{
	public function __construct() { 
		parent::__construct('Transaction Failed', 'CWSTransactionFailedFault');
	}
}

# 327
class VelocityCWSTransactionAlreadySettledFault extends VelocityError
{
	public function __construct() { 
		parent::__construct('Transaction Already Settled', 'CWSTransactionAlreadySettledFault');
	}
}

# 328
class VelocityCWSConnectionFault extends VelocityError
{
	public function __construct() { 
		parent::__construct('Connection Failure', 'CWSConnectionFault');
	}
}

# 400
class VelocityBadRequestError extends VelocityError
{
	public function __construct() { 
		parent::__construct('Bad Request', 'badRequestError');
	}
}

# 401
class VelocitySystemFault extends VelocityError
{
	public function __construct() { 
		parent::__construct('System problem', 'SystemFault');
	}
}

# 406
class VelocityAuthenticationFault extends VelocityError
{
	public function __construct() { 
		parent::__construct('Authentication Failure', 'AuthenticationFault');
	}
}

# 412
class VelocitySTSUnavailableFault extends VelocityError
{
	public function __construct() { 
		parent::__construct('Security Token Service is unavailable', 'STSUnavailableFault');
	}
}

# 413
class VelocityAuthorizationFault extends VelocityError
{
	public function __construct() { 
		parent::__construct('Authorization Failure', '_AuthorizationFault');
	}
}

# 415
class VelocityClaimNotFoundFault extends VelocityError
{
	public function __construct() { 
		parent::__construct('Claim Not Found', 'ClaimNotFoundFault');
	}
}

# 416
class VelocityAccessClaimNotFoundFault extends VelocityError
{
	public function __construct() { 
		parent::__construct('Access Claim Not Found', 'AccessClaimNotFoundFault');
	}
}

# 420
class VelocityDuplicateClaimFault extends VelocityError
{
	public function __construct() { 
		parent::__construct('Duplicate Claim', 'DuplicateClaimFault');
	}
}

# 421
class VelocityDuplicateUserFault extends VelocityError
{
	public function __construct() { 
		parent::__construct('Duplicate User', 'DuplicateUserFault');
	}
}

# 422
class VelocityClaimTypeNotAllowedFault extends VelocityError
{
	public function __construct() { 
		parent::__construct('Claim Type Not Allowed', 'ClaimTypeNotAllowedFault');
	}
}

# 423
class VelocityClaimSecurityDomainMismatchFault extends VelocityError
{
	public function __construct() { 
		parent::__construct('Claim Security Domain Mismatch', 'ClaimSecurityDomainMismatchFault');
	}
}

# 424
class VelocityClaimPropertyValidationFault extends VelocityError
{
	public function __construct() { 
		parent::__construct('Claim Property Validation', 'ClaimPropertyValidationFault');
	}
}

# 450
class VelocityRelyingPartyNotAssociatedToSecurityDomainFault extends VelocityError
{
	public function __construct() { 
		parent::__construct('Relying Party Not Associated To Security Domain', 'RelyingPartyNotAssociatedToSecurityDomainFault');
	}
}

# 404
class VelocityNotFoundError extends VelocityError
{
	public function __construct() { 
		parent::__construct('Not Forund', 'notFoundError');
	}
}

# 500
class VelocityInternalServerError extends VelocityError
{
	public function __construct() {
		parent::__construct('Internal Server Error', 'internalServerError');
	}
}

# 503
class VelocityServiceUnavailableError extends VelocityError
{
	public function __construct() { 
		parent::__construct('Service Unavailable', 'ServiceUnavailableError');
	}
}

# 504
class VelocityGatewayTimeoutError extends VelocityError
{
	public function __construct() { 
		parent::__construct('Gateway Time out', 'GatewayTimeoutError');
	}
}

# 5005
class VelocityInvalidTokenFault extends VelocityError
{
	public function __construct() { 
		parent::__construct('Invalid Token', 'InvalidTokenFault');
	}
}

#9999
class VelocityCWSTransactionServiceUnavailableFault extends VelocityError
{
	public function __construct() { 
		parent::__construct('Transaction Service Unavailable', 'CWSTransactionServiceUnavailableFault');
	}
}

# Everything else
class VelocityUnexpectedError extends VelocityError
{
	public function __construct($message) {
		parent::__construct($message, 'unexpectedError');
	}
}
