Attribute VB_Name = "api_user32"
Option Explicit

Public Declare Function RegisterWindowMessageW Lib "user32.dll" _
    (ByVal lpString As Long) As Long
    
Public Declare Function GetSysColor Lib "user32" _
    (ByVal nIndex As Long) As Long

'Public Const CTLCOLOR_MSGBOX = 0
'Public Const CTLCOLOR_EDIT = 1
'Public Const CTLCOLOR_LISTBOX = 2
'Public Const CTLCOLOR_BTN = 3
'Public Const CTLCOLOR_DLG = 4
'Public Const CTLCOLOR_SCROLLBAR = 5
'Public Const CTLCOLOR_STATIC = 6
'Public Const CTLCOLOR_MAX = 7

Public Const COLOR_SCROLLBAR = 0
Public Const COLOR_BACKGROUND = 1
Public Const COLOR_ACTIVECAPTION = 2
Public Const COLOR_INACTIVECAPTION = 3
Public Const COLOR_MENU = 4
Public Const COLOR_WINDOW = 5
Public Const COLOR_WINDOWFRAME = 6
Public Const COLOR_MENUTEXT = 7
Public Const COLOR_WINDOWTEXT = 8
Public Const COLOR_CAPTIONTEXT = 9
Public Const COLOR_ACTIVEBORDER = 10
Public Const COLOR_INACTIVEBORDER = 11
Public Const COLOR_APPWORKSPACE = 12
Public Const COLOR_HIGHLIGHT = 13
Public Const COLOR_HIGHLIGHTTEXT = 14
Public Const COLOR_BTNFACE = 15
Public Const COLOR_BTNSHADOW = 16
Public Const COLOR_GRAYTEXT = 17
Public Const COLOR_BTNTEXT = 18
Public Const COLOR_INACTIVECAPTIONTEXT = 19
Public Const COLOR_BTNHIGHLIGHT = 20
Public Const COLOR_3DDKSHADOW = 21
Public Const COLOR_3DLIGHT = 22
Public Const COLOR_INFOTEXT = 23
Public Const COLOR_INFOBK = 24
Public Const COLOR_GRADIENTACTIVECAPTION = 27
Public Const COLOR_GRADIENTINACTIVECAPTION = 28
Public Const COLOR_MENUHILIGHT = 29
Public Const COLOR_MENUBAR = 30

Public Declare Function PrivateExtractIconsW Lib "user32" _
    (ByVal lpszFile As Long, _
     ByVal nIconIndex As Long, _
     ByVal cxIcon As Long, _
     ByVal cyIcon As Long, _
     phicon As Long, _
     piconid As Long, _
     ByVal nICons As Long, _
     ByVal flags As Long) As Long
     
Public Declare Function CreateIcon Lib "user32" _
    (ByVal hInstance As Long, _
     ByVal nWidth As Long, _
     ByVal nHeight As Long, _
     ByVal cPlanes As Long, _
     ByVal cBitsPixel As Long, _
     ByVal lpbANDbits As Long, _
     ByVal lpbXORbits As Long) As Long
          
Public Declare Function DestroyIcon Lib "user32" _
    (ByVal hIcon As Long) As Long

Public Declare Function DrawIconEx Lib "user32" _
    (ByVal hDC As Long, _
     ByVal xLeft As Long, _
     ByVal yTop As Long, _
     ByVal hIcon As Long, _
     ByVal cxWidth As Long, _
     ByVal cyWidth As Long, _
     ByVal istepIfAniCur As Long, _
     ByVal hbrFickerFreeDraw As Long, _
     ByVal diFlags As Long) As Long

Public Const DI_COMPAT = 4
Public Const DI_DEFAULTSIZE = 8
Public Const DI_IMAGE = 2
Public Const DI_MASK = 1
Public Const DI_NORMAL = 3
Public Const DI_APPBANDING = 1


Public Declare Function EnumDisplaySettingsW Lib "user32" _
    (ByVal lpszDeviceName As Long, _
     ByVal iModeNum As Long, _
     lpDevMode As DEVMODE) As Long

Public Declare Function ChangeDisplaySettingsW Lib "user32" _
    (lpDevMode As DEVMODE, _
     ByVal dwFlags As Long) As Long
     
Public Const ENUM_CURRENT_SETTINGS = -1
Public Const CCDEVICENAME = 32
Public Const CCFORMNAME = 32
Public Const CCHDEVICENAME = 32
Public Const CCHFORMNAME = 32

Public Type DEVMODE
    dmDeviceName(CCDEVICENAME * 2 - 1) As Byte
    dmSpecVersion As Integer
    dmDriverVersion As Integer
    dmSize As Integer
    dmDriverExtra As Integer
    dmFields As Long
    
    dmOrientation As Integer
    dmPaperSize As Integer
    dmPaperLength As Integer
    dmPaperWidth As Integer
    dmScale As Integer
    dmCopies As Integer
    dmDefaultSource As Integer
    dmPrintQuality As Integer
    
    dmColor As Integer
    dmDuplex As Integer
    dmYResolution As Integer
    dmTTOption As Integer
    dmCollate As Integer
    dmFormName(CCHFORMNAME * 2 - 1) As Byte
    'dmLogPixels As Long
    dmBitsPerPel As Long
    dmPelsWidth As Long
    dmPelsHeight As Long
    dmDisplayFlags As Long
    dmDisplayFrequency As Long
    
    'dmICMMethod As Long
    'dmICMIntent As Long
    'dmMediaType As Long
    'dmDitherType As Long
    'dmReserved1 As Long
    'dmReserved2 As Long
    'dmPanningWidth As Long
    'dmPanningHeight As Long
End Type

Public Const DM_BITSPERPEL = &H40000
Public Const DM_PELSWIDTH = &H80000
Public Const DM_PELSHEIGHT = &H100000
Public Const DM_DISPLAYFREQUENCY = &H400000

Public Const CDS_UPDATEREGISTRY = &H1
Public Const CDS_TEST = &H2

Public Const DISP_CHANGE_SUCCESSFUL = 0
Public Const DISP_CHANGE_RESTART = 1
Public Const BITSPIXEL = 12

Public Declare Function SystemParametersInfoW Lib "user32" _
    (ByVal uAction As Long, _
     ByVal uParam As Long, _
     ByVal lpvParam As Long, _
     ByVal fuWinIni As Long) As Long

Public Const SPI_SETMOUSESPEED = 113

Public Const SPIF_SENDWININICHANGE = &H2
Public Const SPIF_UPDATEINIFILE = &H1

Public Declare Function GetCursorPos Lib "user32" _
    (ByVal lpPoint As Long) As Long
    
Public Declare Function GetWindowDC Lib "user32" _
    (ByVal hWnd As Long) As Long

Public Declare Sub GetClientRect Lib "user32" _
    (ByVal hWnd As Long, _
     lpRect As RECT)

Public Declare Function FillRect Lib "user32" _
    (ByVal hDC As Long, _
     lpRect As RECT, _
     ByVal hBrush As Long) As Long

Public Declare Function ClientToScreen Lib "user32" _
    (ByVal hWnd As Long, _
     lpPoint As POINTAPI) As Long


Public Declare Function GetAsyncKeyState Lib "user32" _
    (ByVal vKey As Long) As Integer

Public Type ACCEL
    fVirt As Byte
    vKey As Integer
    CmdId As Integer
End Type

Public Const FALT = 16
Public Const FCONTROL = 8
Public Const FNOINVERT = 2
Public Const FSHIFT = 4
Public Const FVIRTKEY = 1


Public Declare Function CreateAcceleratorTableW Lib "user32" _
    (lpaccl As ACCEL, _
     ByVal cEntries As Long) As Long

Public Declare Function DestroyAcceleratorTable Lib "user32" _
    (ByVal hAccel As Long) As Long

Public Declare Function WindowFromDC Lib "user32" _
    (ByVal hDC As Long) As Long

Public Type ICONINFO
    fIcon As Long
    xHotspot As Long
    yHotspot As Long
    hbmMask As Long
    hbmColor As Long
End Type

Public Declare Function CreateIconIndirect Lib "user32" _
    (piconinfo As ICONINFO) As Long


Public Declare Function FlashWindow Lib "user32" _
    (ByVal hWnd As Long, _
     ByVal bInvert As Long) As Long

