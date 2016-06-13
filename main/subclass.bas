Attribute VB_Name = "subclass"
Option Explicit

Public OldMainFormProc As Long

Public Function NewMainFormProc(ByVal hWnd As Long, ByVal uMsg As Long, ByVal wParam As Long, ByVal lParam As Long) As Long
    Dim nH As NMHDR
    Dim FD As Long
    
    Select Case uMsg
        Case WM_ERROR_SOCKET
            MainForm.WmErrorSocket wParam, lParam
        
        Case WM_TRAYICONCLICK
            If lParam = WM_RBUTTONUP Then
                ShowWindow hWnd, SW_RESTORE
                SetForegroundWindow hWnd
            End If
            
        Case WM_TASKBARCREATED
            Call ServerTrayIconAdd(MainForm.hWnd, MainForm.Icon.Handle, MainForm.Caption)
    End Select
    
    NewMainFormProc = CallWindowProc(OldMainFormProc, hWnd, uMsg, wParam, lParam)
End Function
