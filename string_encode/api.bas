Attribute VB_Name = "api"
Option Explicit

Public Const MAX_PATH = 260
Public Const ERROR_SUCCESS = 0
Public Const NO_ERROR = 0
Public Const ICON_SMALL = 0
Public Const ICON_BIG = 1

Public Declare Function CloseHandle Lib "Kernel32" _
    (ByVal hObject As Long) As Long

Public Declare Sub Sleep Lib "Kernel32" _
    (ByVal dwMilliseconds As Long)

Public Declare Sub CopyMemory Lib "Kernel32" Alias "RtlMoveMemory" _
    (ByVal lpvDest As Long, _
     ByVal lpvSource As Long, _
     ByVal cbCopy As Long)

Public Declare Function RtlCompareMemory Lib "ntdll" _
    (ByVal lpvSource1 As Long, _
     ByVal lpvSource2 As Long, _
     ByVal nLength As Long) As Long

Public Declare Sub RtlZeroMemory Lib "Kernel32" _
    (ByVal Destination As Long, _
     ByVal nLength As Long)

Public Declare Function CompareStringW Lib "Kernel32" _
    (ByVal Locale As Long, _
     ByVal swCmpFlags As Long, _
     ByVal lpString1 As Long, _
     ByVal cchCount1 As Long, _
     ByVal lpString2 As Long, _
     ByVal cchCount2 As Long) As Long

Public Const LOCALE_SYSTEM_DEFAULT = &H800
Public Const CSTR_LESS_THAN = 1
Public Const CSTR_EQUAL = 2
Public Const CSTR_GREATER_THAN = 3

Public Declare Function GetComputerNameW Lib "Kernel32" _
    (ByVal lpBuffer As Long, _
     lpnSize As Long) As Long

Public Declare Function WaitForSingleObject Lib "Kernel32" _
    (ByVal hHandle As Long, _
     ByVal dwMilliseconds As Long) As Long

Public Declare Function WaitForMultipleObjects Lib "Kernel32" _
    (ByVal nCount As Long, _
     lpHandles As Long, _
     ByVal bWaitAll As Long, _
     ByVal dwMilliseconds As Long) As Long
     
Public Const INFINITE = &HFFFFFFFF
Public Const WAIT_ABANDONED = &H80
Public Const WAIT_OBJECT_0 = &H0
Public Const WAIT_TIMEOUT = &H102
Public Declare Sub GetLocalTime Lib "Kernel32" _
    (lpSystemTime As SYSTEMTIME)
    
Public Type SYSTEMTIME
    wYear As Integer
    wMonth As Integer
    wDayOfWeek As Integer
    wDay As Integer
    wHour As Integer
    wMinute As Integer
    wSecond As Integer
    wMilliseconds As Integer
End Type

Public Declare Function DrawTextW Lib "user32" _
    (ByVal hdc As Long, _
     ByVal lpString As Long, _
     ByVal nCount As Long, _
     lpRect As RECT, _
     ByVal uFormat As Long) As Long

Public Const DT_LEFT = &H0
Public Const DT_CENTER = &H1
Public Const DT_RIGHT = &H2
Public Const DT_TOP = &H0
Public Const DT_VCENTER = &H4
Public Const DT_BOTTOM = &H8
Public Const DT_WORDBREAK = &H10
Public Const DT_SINGLELINE = &H20
Public Const DT_EXPANDTABS = &H40
Public Const DT_TABSTOP = &H80
Public Const DT_NOCLIP = &H100
Public Const DT_EXTERNALLEADING = &H200
Public Const DT_CALCRECT = &H400
Public Const DT_NOPREFIX = &H800
Public Const DT_INTERNAL = &H1000
Public Const DT_EDITCONTROL = &H2000
Public Const DT_PATH_ELLIPSIS = &H4000
Public Const DT_END_ELLIPSIS = &H8000
Public Const DT_MODIFYSTRING = &H10000
Public Const DT_RTLREADING = &H20000
Public Const DT_WORD_ELLIPSIS = &H40000
Public Const DT_NOFULLWIDTHCHARBREAK = &H80000
Public Const DT_HIDEPREFIX = &H100000
Public Const DT_PREFIXONLY = &H200000


Public Declare Function GetCurrentProcessId Lib "Kernel32" () As Long

Public Declare Function GetProcessId Lib "Kernel32" _
    (ByVal hProcess As Long) As Long

Public Declare Function TerminateProcess Lib "Kernel32" _
    (ByVal hProcess As Long, _
     ByVal uExitCode As Long) As Long


Public Declare Function GetDriveTypeW Lib "Kernel32" _
    (ByVal lpRootPathName As Long) As Long
    
Public Const DRIVE_UNKNOWN = 0
Public Const DRIVE_NO_ROOT_DIR = 1
Public Const DRIVE_REMOVABLE = 2
Public Const DRIVE_FIXED = 3
Public Const DRIVE_REMOTE = 4
Public Const DRIVE_CDROM = 5
Public Const DRIVE_RAMDISK = 6

Public Declare Function GetLogicalDriveStringsW Lib "Kernel32" _
    (ByVal nBufferLength As Long, _
     ByVal lpBuffer As Long) As Long

