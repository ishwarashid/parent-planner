# Kill Switch Implementation Plan (Cryptographic)

This document outlines a secure plan to implement a remote "kill switch" for the application using public key cryptography. This approach is secure, does not require ongoing server access, and cannot be tampered with by the client.

## Core Concepts

The system is based on a cryptographic key pair:

1.  **Private Key:** A key that only the developer holds. It is kept secret and is used to sign commands.
2.  **Public Key:** A key that is stored within the Laravel application's configuration. It is not secret and is used to verify commands.
3.  **Signed Message:** A command (e.g., "disable") that has been cryptographically signed with the private key. Only the corresponding public key can verify that the signature is authentic.
4.  **State File:** A simple file on the server (`storage/app/license.state`) that holds the current status of the application (`enabled` or `disabled`). This is the "source of truth" for the enforcement middleware.

## Why This Approach is Secure

*   **No Secrets on Client Server:** The client only has the public key, which cannot be used to create new commands.
*   **No Server Access Required:** The developer can generate and send a signed command from any machine.
*   **Tamper-Proof:** The client cannot modify the public key or the verification logic without breaking the system. The system can be designed to "fail-safe" (disable the app) if the verification mechanism is tampered with.

## Implementation Steps

### 1. Generate Key Pair

A secure RSA key pair will be generated. The developer will receive:
*   `private.key`: To be kept secret.
*   `public.key`: To be placed in the application.

### 2. Store Public Key

The content of `public.key` will be stored in the Laravel application's configuration, likely in `config/services.php`.

```php
// in config/services.php
'license' => [
    'public_key' => '-----BEGIN PUBLIC KEY-----...-----END PUBLIC KEY-----',
],
```

### 3. Create the State File

A file will be created at `storage/app/license.state`. Its initial content will be `enabled`.

### 4. Create the Command Endpoint

A single, public endpoint will be created at `/license/command`. This endpoint will:
*   Accept a `POST` request.
*   Expect a JSON payload containing the command and the signature, e.g., `{"command": "disable", "signature": "..."}`.

### 5. Implement Verification Logic

The controller handling the `/license/command` endpoint will perform the following steps:
1.  Receive the JSON payload.
2.  Extract the `command` and the `signature`.
3.  Use the public key from the configuration to verify that the `signature` is a valid signature for the `command`.
4.  **If verification succeeds:**
    *   Check the value of the `command` (`enable` or `disable`).
    *   Update the content of the `storage/app/license.state` file accordingly.
    *   Return a success response.
5.  **If verification fails:**
    *   Log the attempt.
    *   Return a `403 Forbidden` error.

### 6. Implement Enforcement Middleware

A middleware (`CheckLicenseStatus`) will be created and applied to all necessary routes. On every request, it will:
1.  Read the content of `storage/app/license.state`.
2.  If the content is not `enabled`, it will abort the request and show a "Service Unavailable" page (`503`).
3.  If the content is `enabled`, it will allow the request to proceed.

### 7. Create a Signing Tool

A simple, standalone PHP script will be provided to the developer. This tool will:
1.  Ask for the path to the `private.key` file.
2.  Ask for the command to be signed (e.g., "disable").
3.  Generate a base64-encoded signature.
4.  Output the final JSON payload to be sent to the application's endpoint.

This provides a complete, secure, and professional solution for remote application management.
