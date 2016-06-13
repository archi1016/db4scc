Attribute VB_Name = "func_server"
Option Explicit

Public WM_TASKBARCREATED As Long
Public Const WM_TRAYICONCLICK = WM_USER + 168
Public Const TRAY_ICON_ID = 58
Public Const TASKBAR_CREATED = "TaskbarCreated"

Public Sub ServerTrayIconAdd(ByVal hWnd As Long, ByVal hIcon As Long, ByVal Msg As String)
    Dim nID As NOTIFYICONDATA
    
    Msg = Msg + vbNullChar
    With nID
        .cbSize = Len(nID)
        .hIcon = hIcon
        .hWnd = hWnd
        CopyMemory VarPtr(.szTip(0)), StrPtr(Msg), Len(Msg) * 2
        .uCallbackMessage = WM_TRAYICONCLICK
        .uFlags = NIF_ICON Or NIF_TIP Or NIF_MESSAGE
        .uID = TRAY_ICON_ID
     End With
   
    Shell_NotifyIconW NIM_ADD, nID
End Sub

Public Sub ServerTrayIconRemove(ByVal hWnd As Long)
    Dim nID As NOTIFYICONDATA
    
    With nID
        .cbSize = Len(nID)
        .hWnd = hWnd
        .uID = TRAY_ICON_ID
    End With
    Shell_NotifyIconW NIM_DELETE, nID
End Sub
