Attribute VB_Name = "func"
Option Explicit

Sub Main()
    Call InitCommonControls
    MainForm.Show
End Sub

Public Sub MsgError(ByVal M As String)
    MsgBox M, vbExclamation, "提示"
End Sub

Public Sub MsgInfo(ByVal M As String)
    MsgBox M, vbInformation, "訊息"
End Sub

Public Function MsgQuestion(ByVal M As String) As Boolean
    MsgQuestion = (MsgBox(M, vbQuestion Or vbYesNo, "詢問") = vbYes)
End Function

Public Function GetSelectedFile(ByVal hWnd As Long, ByVal sTitle As String, ByVal sFileReadme As String, ByVal sFilter As String) As String
    Dim OFN As OPENFILENAME
    Dim lpstrFilter As String
    Dim lpstrFile As String
    
    GetSelectedFile = ""
    lpstrFilter = sFileReadme + " (" + sFilter + ")" + vbNullChar + sFilter + vbNullChar + vbNullChar
    lpstrFile = String$(MAX_PATH, vbNullChar)
    With OFN
        .lStructSize = Len(OFN)
        .hWndOwner = hWnd
        .lpstrFilter = StrPtr(lpstrFilter)
        .lpstrFile = StrPtr(lpstrFile)
        .nMaxFile = Len(lpstrFile)
        .lpstrTitle = StrPtr(sTitle)
        .flags = OFN_EXPLORER Or OFN_DONTADDTORECENT Or OFN_ENABLESIZING Or OFN_FILEMUSTEXIST Or OFN_NODEREFERENCELINKS Or OFN_NONETWORKBUTTON Or OFN_PATHMUSTEXIST
    End With
    If GetOpenFileNameW(OFN) <> 0 Then
        GetSelectedFile = StrCutNull(lpstrFile)
    End If
End Function

Public Function GetSaveAsFile(ByVal hWnd As Long, ByVal sTitle As String, ByVal sFileReadme As String, ByVal sFilter As String, ByVal sDefExt As String) As String
    Dim OFN As OPENFILENAME
    Dim lpstrFilter As String
    Dim lpstrFile As String
    
    GetSaveAsFile = ""
    lpstrFilter = sFileReadme + " (" + sFilter + ")" + vbNullChar + sFilter + vbNullChar + vbNullChar
    lpstrFile = String$(MAX_PATH, vbNullChar)
    With OFN
        .lStructSize = Len(OFN)
        .hWndOwner = hWnd
        .lpstrFilter = StrPtr(lpstrFilter)
        
        .lpstrDefExt = StrPtr(sDefExt)
        .lpstrFile = StrPtr(lpstrFile)
        .nMaxFile = Len(lpstrFile)
        .lpstrTitle = StrPtr(sTitle)
        .flags = OFN_EXPLORER Or OFN_DONTADDTORECENT Or OFN_ENABLESIZING Or OFN_NONETWORKBUTTON Or OFN_PATHMUSTEXIST Or OFN_OVERWRITEPROMPT
    End With
    If GetSaveFileNameW(OFN) <> 0 Then
        GetSaveAsFile = StrCutNull(lpstrFile)
    End If
End Function

Public Function DumpMemoryToFile(ByVal lpMemory As Long, ByVal nSize As Long, ByVal FP As String) As Boolean
    Dim hFile As Long
    Dim Ret As Long
    
    DumpMemoryToFile = False
    DeleteFileW StrPtr(FP)
    hFile = CreateFileW(StrPtr(FP), GENERIC_WRITE, FILE_SHARE_READ, 0, CREATE_NEW, FILE_FLAG_SEQUENTIAL_SCAN, 0)
    If hFile <> INVALID_HANDLE_VALUE Then
        WriteFile hFile, lpMemory, nSize, Ret, 0
        CloseHandle hFile
        DumpMemoryToFile = True
    End If
End Function

Public Function StrCutNull(ByVal S As String) As String
    Dim I As Long
    
    If Len(S) > 0 Then
        I = InStr(S, vbNullChar)
        If I > 0 Then
            StrCutNull = Left$(S, I - 1)
        Else
            StrCutNull = S
        End If
    Else
        StrCutNull = ""
    End If
End Function

Public Function GetSelectedFolder(ByVal hWnd As Long, ByVal sTitle As String) As String
    Dim BI As BROWSEINFO
    Dim DN As String
    Dim Ret As Long
    
    GetSelectedFolder = ""
    DN = String$(MAX_PATH, vbNullChar)
    With BI
        .hWndOwner = hWnd
        .pszDisplayName = StrPtr(DN)
        .lpszTitle = StrPtr(sTitle)
        .ulFlags = BIF_USENEWUI Or BIF_RETURNONLYFSDIRS Or BIF_DONTGOBELOWDOMAIN Or BIF_RETURNFSANCESTORS
    End With
    
    Ret = SHBrowseForFolderW(BI)
    If Ret <> 0 Then
        If SHGetPathFromIDListW(Ret, StrPtr(DN)) <> 0 Then
            GetSelectedFolder = StrCutNull(DN)
        End If
        CoTaskMemFree Ret
    End If
End Function

Public Function GetMachineName() As String
    Dim Ret As Long
    
    Ret = MAX_PATH
    GetMachineName = String$(Ret, vbNullChar)
    GetComputerNameW StrPtr(GetMachineName), Ret
    GetMachineName = StrCutNull(GetMachineName)
End Function

Public Function ConvIpToStr(ByVal nAddr As Long) As String
    Dim B(3) As Byte
    
    CopyMemory VarPtr(B(0)), VarPtr(nAddr), 4
    ConvIpToStr = CStr(B(0)) + "." + CStr(B(1)) + "." + CStr(B(2)) + "." + CStr(B(3))
End Function

Public Sub WriteStrToIniFile(ByVal IniFile As String, ByVal KeyName As String, ByVal ValueStr As String)
    Dim Ret As Long
    
    If Mid$(IniFile, 2, 2) <> ":\" Then IniFile = App.Path + "\" + IniFile
    If ValueStr <> "" Then
        WritePrivateProfileStringW StrPtr("CONFIG"), StrPtr(KeyName), StrPtr(ValueStr), StrPtr(IniFile)
    Else
        WritePrivateProfileStringW StrPtr("CONFIG"), StrPtr(KeyName), 0, StrPtr(IniFile)
    End If
End Sub
