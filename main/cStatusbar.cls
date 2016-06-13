VERSION 1.0 CLASS
BEGIN
  MultiUse = -1  'True
  Persistable = 0  'NotPersistable
  DataBindingBehavior = 0  'vbNone
  DataSourceBehavior  = 0  'vbNone
  MTSTransactionMode  = 0  'NotAnMTSObject
END
Attribute VB_Name = "cStatusBar"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = True
Attribute VB_PredeclaredId = False
Attribute VB_Exposed = False
Option Explicit

Public hWnd As Long

Private Sub Class_Initialize()
    hWnd = 0
End Sub

Private Sub Class_Terminate()
    If hWnd <> 0 Then DestroyWindow hWnd
End Sub

Public Sub Create(ByVal hWndOfParent As Long)
    hWnd = CreateWindowExW( _
            0, _
            StrPtr(STATUSCLASSNAME), _
            0, _
            WS_CHILD Or WS_VISIBLE Or SBARS_TOOLTIPS, _
            0, _
            0, _
            0, _
            0, _
            hWndOfParent, _
            0, _
            App.hInstance, _
            0)
End Sub

Public Sub Create2(ByVal hWndOfParent As Long)
    hWnd = CreateWindowExW( _
            0, _
            StrPtr(STATUSCLASSNAME), _
            0, _
            WS_CHILD Or WS_VISIBLE Or SBARS_TOOLTIPS Or SBARS_SIZEGRIP, _
            0, _
            0, _
            0, _
            0, _
            hWndOfParent, _
            0, _
            App.hInstance, _
            0)
End Sub

Public Sub SetFontFromhWnd(ByVal fromhWnd As Long)
    SendMessageW hWnd, WM_SETFONT, SendMessageW(fromhWnd, WM_GETFONT, 0, 0), 1
End Sub

Public Sub SetParts(PartWidths() As Long)
    SendMessageW hWnd, SB_SETPARTS, UBound(PartWidths) + 1, VarPtr(PartWidths(0))
End Sub

Public Sub SetPartsByCsvStr(ByVal csvStr As String)
    Dim S() As String
    Dim N() As Long
    Dim I As Long
    
    S = Split(csvStr, ",")
    ReDim N(UBound(S))
    For I = 0 To UBound(S)
        N(I) = CLng(S(I))
    Next
    Call SetParts(N)
    
    Erase S
    Erase N
End Sub

Public Sub SetPartText(ByVal nPart As Long, ByVal T As String)
    SendMessageW hWnd, SB_SETTEXTW, nPart, StrPtr(T)
End Sub

Public Sub SetPartTipText(ByVal nPart As Long, ByVal T As String)
    SendMessageW hWnd, SB_SETTIPTEXTW, nPart, StrPtr(T)
End Sub

Public Sub Resize()
    SendMessageW hWnd, WM_SIZE, 0, 0
End Sub

Public Sub Show()
    ShowWindow hWnd, SW_SHOW
    DoEvents
End Sub

Public Sub Hide()
    ShowWindow hWnd, SW_HIDE
End Sub

Public Function GetBarHeight() As Long
    Dim RC As RECT

    SendMessageW hWnd, SB_GETRECT, 0, VarPtr(RC)
    GetBarHeight = RC.Bottom - RC.Top + 2
End Function
