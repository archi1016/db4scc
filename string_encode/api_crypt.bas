Attribute VB_Name = "api_crypt"
Option Explicit

Public Declare Function CryptAcquireContextW Lib "advapi32" _
    (phProv As Long, _
     ByVal pszContainer As Long, _
     ByVal pszProvider As Long, _
     ByVal dwProvType As Long, _
     ByVal dwFlags As Long) As Long

Public Declare Function CryptReleaseContext Lib "advapi32" _
    (ByVal hProv As Long, _
     ByVal dwFlags As Long) As Long
     
Public Const PROV_RSA_FULL = 1
Public Const CRYPT_VERIFYCONTEXT = &HF0000000

Public Declare Function CryptCreateHash Lib "advapi32" _
    (ByVal hProv As Long, _
     ByVal Algid As Long, _
     ByVal hKey As Long, _
     ByVal dwFlags As Long, _
     phHash As Long) As Long

Public Declare Function CryptDestroyHash Lib "advapi32" _
    (ByVal hHash As Long) As Long
     
Public Const CALG_MD5 = &H18003 - &H10000
Public Const CALG_SHA = &H18004 - &H10000

Public Declare Function CryptHashData Lib "advapi32" _
    (ByVal hHash As Long, _
     ByVal pbData As Long, _
     ByVal dwDataLen As Long, _
     ByVal dwFlags As Long) As Long

Public Declare Function CryptGetHashParam Lib "advapi32" _
    (ByVal hHash As Long, _
     ByVal dwParam As Long, _
     ByVal pbData As Long, _
     pdwDataLen As Long, _
     ByVal dwFlags As Long) As Long

Public Const HP_HASHVAL = &H2

Public Const CRYPTPROTECT_UI_FORBIDDEN = 1

Public Declare Function CryptDeriveKey Lib "advapi32" _
    (ByVal hProv As Long, _
     ByVal Algid As Long, _
     ByVal hBaseData As Long, _
     ByVal dwFlags As Long, _
     phKey As Long) As Long

Public Declare Function CryptDestroyKey Lib "advapi32" _
    (ByVal hKey As Long) As Long

