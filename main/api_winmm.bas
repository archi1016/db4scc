Attribute VB_Name = "api_winmm"
Option Explicit

Public Declare Function PlaySoundW Lib "winmm" _
    (ByVal pszSound As Long, _
     ByVal hmod As Long, _
     ByVal fdwSound As Long) As Long

Public Const SND_SYNC = &H0
Public Const SND_ASYNC = &H1
Public Const SND_NODEFAULT = &H2
Public Const SND_MEMORY = &H4
Public Const SND_LOOP = &H8
Public Const SND_NOSTOP = &H10
Public Const SND_PURGE = &H40
Public Const SND_APPLICATION = &H80
Public Const SND_NOWAIT = &H2000
Public Const SND_ALIAS = &H10000
Public Const SND_FILENAME = &H20000
Public Const SND_RESOURCE = &H40004
Public Const SND_ALIAS_ID = &H110000

