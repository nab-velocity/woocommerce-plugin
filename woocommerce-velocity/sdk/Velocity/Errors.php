<?php
class Velocity_Error extends Exception
{
	public function __construct($message, $name) {
		parent::__construct($message);
		$this->name = $name;
	}
}

# 207
class Velocity_CWSFault extends Velocity_Error
{
	public function __construct() { 
		parent::__construct('General CWS fault', 'CWSFault');
	}
}

# 208
class Velocity_CWSInvalidOperationFault extends Velocity_Error
{
	public function __construct() { 
		parent::__construct('Invalid operation is being attempted', 'CWSInvalidOperationFault');
	}
}

# 225
class Velocity_CWSValidationResultFault extends Velocity_Error
{
	public function __construct() { 
		parent::__construct('Xml validation Errors', 'CWSValidationResultFault');
	}
}

# 306
class Velocity_CWSInvalidMessageFormatFault extends Velocity_Error
{
	public function __construct() { 
		parent::__construct('Invalid Message Format', 'CWSInvalidMessageFormatFault');
	}
}

# 312
class Velocity_CWSDeserializationFault extends Velocity_Error
{
	public function __construct() { 
		parent::__construct('Deserialization not Successfull', 'CWSDeserializationFault');
	}
}

# 313
class Velocity_CWSExtendedDataNotSupportedFault extends Velocity_Error
{
	public function __construct() { 
		parent::__construct('Manage Billing Data Not Supported', 'CWSExtendedDataNotSupportedFault');
	}
}

# 314
class Velocity_CWSInvalidServiceConfigFault extends Velocity_Error
{
	public function __construct() { 
		parent::__construct('Invalid Service Configuration', 'CWSInvalidServiceConfigFault');
	}
}

# 317
class Velocity_CWSOperationNotSupportedFault extends Velocity_Error
{
	public function __construct() { 
		parent::__construct('Operation Not Supported', 'CWSOperationNotSupportedFault');
	}
}

# 318
class Velocity_CWSTransactionFailedFault extends Velocity_Error
{
	public function __construct() { 
		parent::__construct('Transaction Failed', 'CWSTransactionFailedFault');
	}
}

# 327
class Velocity_CWSTransactionAlreadySettledFault extends Velocity_Error
{
	public function __construct() { 
		parent::__construct('Transaction Already Settled', 'CWSTransactionAlreadySettledFault');
	}
}

# 328
class Velocity_CWSConnectionFault extends Velocity_Error
{
	public function __construct() { 
		parent::__construct('Connection Failure', 'CWSConnectionFault');
	}
}

# 400
class Velocity_BadRequestError extends Velocity_Error
{
	public function __construct() { 
		parent::__construct('Bad Request', 'badRequestError');
	}
}

# 401
class Velocity_SystemFault extends Velocity_Error
{
	public function __construct() { 
		parent::__construct('System problem', 'SystemFault');
	}
}

# 406
class Velocity_AuthenticationFault extends Velocity_Error
{
	public function __construct() { 
		parent::__construct('Authentication Failure', 'AuthenticationFault');
	}
}

# 412
class Velocity_STSUnavailableFault extends Velocity_Error
{
	public function __construct() { 
		parent::__construct('Security Token Service is unavailable', 'STSUnavailableFault');
	}
}

# 413
class Velocity_AuthorizationFault extends Velocity_Error
{
	public function __construct() { 
		parent::__construct('Authorization Failure', '_AuthorizationFault');
	}
}

# 415
class Velocity_ClaimNotFoundFault extends Velocity_Error
{
	public function __construct() { 
		parent::__construct('Claim Not Found', 'ClaimNotFoundFault');
	}
}

# 416
class Velocity_AccessClaimNotFoundFault extends Velocity_Error
{
	public function __construct() { 
		parent::__construct('Access Claim Not Found', 'AccessClaimNotFoundFault');
	}
}

# 420
class Velocity_DuplicateClaimFault extends Velocity_Error
{
	public function __construct() { 
		parent::__construct('Duplicate Claim', 'DuplicateClaimFault');
	}
}

# 421
class Velocity_DuplicateUserFault extends Velocity_Error
{
	public function __construct() { 
		parent::__construct('Duplicate User', 'DuplicateUserFault');
	}
}

# 422
class Velocity_ClaimTypeNotAllowedFault extends Velocity_Error
{
	public function __construct() { 
		parent::__construct('Claim Type Not Allowed', 'ClaimTypeNotAllowedFault');
	}
}

# 423
class Velocity_ClaimSecurityDomainMismatchFault extends Velocity_Error
{
	public function __construct() { 
		parent::__construct('Claim Security Domain Mismatch', 'ClaimSecurityDomainMismatchFault');
	}
}

# 424
class Velocity_ClaimPropertyValidationFault extends Velocity_Error
{
	public function __construct() { 
		parent::__construct('Claim Property Validation', 'ClaimPropertyValidationFault');
	}
}

# 450
class Velocity_RelyingPartyNotAssociatedToSecurityDomainFault extends Velocity_Error
{
	public function __construct() { 
		parent::__construct('Relying Party Not Associated To Security Domain', 'RelyingPartyNotAssociatedToSecurityDomainFault');
	}
}

# 404
class Velocity_NotFoundError extends Velocity_Error
{
	public function __construct() { 
		parent::__construct('Not Forund', 'notFoundError');
	}
}

# 500
class Velocity_InternalServerError extends Velocity_Error
{
	public function __construct() {
		parent::__construct('Internal Server Error', 'internalServerError');
	}
}

# 503
class Velocity_ServiceUnavailableError extends Velocity_Error
{
	public function __construct() { 
		parent::__construct('Service Unavailable', 'ServiceUnavailableError');
	}
}

# 504
class Velocity_GatewayTimeoutError extends Velocity_Error
{
	public function __construct() { 
		parent::__construct('Gateway Time out', 'GatewayTimeoutError');
	}
}

# 5005
class Velocity_InvalidTokenFault extends Velocity_Error
{
	public function __construct() { 
		parent::__construct('Invalid Token', 'InvalidTokenFault');
	}
}

#9999
class Velocity_CWSTransactionServiceUnavailableFault extends Velocity_Error
{
	public function __construct() { 
		parent::__construct('Transaction Service Unavailable', 'CWSTransactionServiceUnavailableFault');
	}
}

# Everything else
class Velocity_UnexpectedError extends Velocity_Error
{
	public function __construct($message) {
		parent::__construct($message, 'unexpectedError');
	}
}
