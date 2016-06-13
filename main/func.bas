Attribute VB_Name = "func"
Option Explicit

Public Sub MsgError(ByVal M As String)
    MsgBox M, vbExclamation, "提示"
End Sub

Public Sub MsgInfo(ByVal M As String)
    MsgBox M, vbInformation, "訊息"
End Sub

Public Function MsgQuestion(ByVal M As String) As Boolean
    MsgQuestion = (MsgBox(M, vbQuestion Or vbYesNo, "詢問") = vbYes)
End Function

Public Function LoadFileToMemory(ByVal FP As String, buf() As Byte, ReadSize As Long) As Boolean
    Dim hFile As Long
    Dim Ret As Long
    
    LoadFileToMemory = False
    
    hFile = CreateFileW(StrPtr(FP), GENERIC_READ, FILE_SHARE_READ, 0, OPEN_EXISTING, FILE_FLAG_SEQUENTIAL_SCAN, 0)
    If hFile <> INVALID_HANDLE_VALUE Then
        ReadSize = GetFileSize(hFile, Ret)
        If ReadSize > 0 Then
            ReDim buf(ReadSize - 1)
            ReadFile hFile, VarPtr(buf(0)), ReadSize, Ret, 0
            
            LoadFileToMemory = True
        End If
        CloseHandle hFile
    End If
End Function

Public Function LaunchProgram(ByVal ExeFile As String, ByVal ExeArgs As String) As Long
    Dim SEI As SHELLEXECUTEINFO
    Dim ExePath As String
    
    LaunchProgram = 0
    
    If Mid$(ExeFile, 2, 2) = ":\" Then
        ExePath = Left$(ExeFile, InStrRev(ExeFile, "\") - 1)
    Else
        ExePath = ""
    End If
    
    With SEI
        .cbSize = Len(SEI)
        .fMask = SEE_MASK_FLAG_NO_UI Or SEE_MASK_NOCLOSEPROCESS
        .lpFile = StrPtr(ExeFile)
        .lpParameters = StrPtr(ExeArgs)
        .lpDirectory = StrPtr(ExePath)
        .nShow = SW_SHOWNORMAL
    End With
    If ShellExecuteExW(SEI) <> 0 Then
        LaunchProgram = SEI.hProcess
    End If
End Function

Public Sub ShellProgram(ByVal ExeFile As String, ByVal ExeArgs As String)
    Dim ExePath As String
    
    If Mid$(ExeFile, 2, 2) = ":\" Then
        ExePath = Left$(ExeFile, InStrRev(ExeFile, "\") - 1)
    Else
        ExePath = ""
    End If
    
    ShellExecuteW 0, 0, StrPtr(ExeFile), StrPtr(ExeArgs), StrPtr(ExePath), SW_SHOWNORMAL
End Sub

Public Function CreateAnyId() As Long
    Dim I As Long
    Dim L(3) As Long
    Dim lpL As Long
    Dim lpCreateAnyId As Long
    
    Randomize Timer
    For I = 0 To 3
        L(I) = CLng(Rnd * 1023)
    Next
    
    lpL = VarPtr(L(0))
    lpCreateAnyId = VarPtr(CreateAnyId)
    CopyMemory lpCreateAnyId + 3, lpL, 1
    CopyMemory lpCreateAnyId + 2, lpL + 4, 1
    CopyMemory lpCreateAnyId + 1, lpL + 8, 1
    CopyMemory lpCreateAnyId, lpL + 12, 1
End Function

Public Sub DeleteFilesAtFolder(ByVal fromFolder As String, ByVal Filters As String)
    Dim FFS() As String
    Dim FFC As Long
    Dim I As Long
    
    If FindFilesAtFolder(fromFolder, Filters, FFS, FFC) Then
        For I = 0 To (FFC - 1)
            DeleteFile fromFolder + "\" + FFS(I)
        Next
    End If
    
    Erase FFS
End Sub

Public Function FindFilesAtFolder(ByVal fromFolder As String, ByVal Filters As String, FFS() As String, FFC As Long) As Boolean
    Dim hFind As Long
    Dim WFD As WIN32_FIND_DATA
    Dim FN As String
    
    FindFilesAtFolder = False
    FFC = 0
    
    fromFolder = fromFolder + "\" + Filters
    hFind = FindFirstFileW(StrPtr(fromFolder), WFD)
    If hFind <> INVALID_HANDLE_VALUE Then
        ReDim FFS(511)
        Do
            With WFD
                .dwFileAttributes = .dwFileAttributes And FILE_ATTRIBUTE_DIRECTORY
                If .dwFileAttributes = 0 Then
                    FN = String$(MAX_PATH, vbNullChar)
                    CopyMemory StrPtr(FN), VarPtr(.cFileName(0)), MAX_PATH * 2
                    FN = StrCutNull(FN)
                    FFS(FFC) = FN
                    FFC = FFC + 1
                End If
            End With
        Loop Until (FindNextFileW(hFind, WFD) = 0)
        FindClose hFind
        FindFilesAtFolder = (FFC > 0)
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

Public Function ReadStrFromIniFile(ByVal IniFile As String, ByVal KeyName As String) As String
    Dim Ret As Long
    
    If Mid$(IniFile, 2, 2) <> ":\" Then IniFile = App.Path + "\" + IniFile
    ReadStrFromIniFile = String$(1024, vbNullChar)
    Ret = GetPrivateProfileStringW(StrPtr("CONFIG"), StrPtr(KeyName), 0, StrPtr(ReadStrFromIniFile), 1024, StrPtr(IniFile))
    ReadStrFromIniFile = Left$(ReadStrFromIniFile, Ret)
End Function

Public Function ReadStrFromIniFileEx(ByVal IniFile As String, ByVal SectionName As String, ByVal KeyName As String) As String
    Dim Ret As Long
    
    If Mid$(IniFile, 2, 2) <> ":\" Then IniFile = App.Path + "\" + IniFile
    ReadStrFromIniFileEx = String$(1024, vbNullChar)
    Ret = GetPrivateProfileStringW(StrPtr(SectionName), StrPtr(KeyName), 0, StrPtr(ReadStrFromIniFileEx), 1024, StrPtr(IniFile))
    ReadStrFromIniFileEx = Left$(ReadStrFromIniFileEx, Ret)
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

Public Sub WriteStrToIniFileEx(ByVal IniFile As String, ByVal SectionName As String, ByVal KeyName As String, ByVal ValueStr As String)
    Dim Ret As Long
    
    If Mid$(IniFile, 2, 2) <> ":\" Then IniFile = App.Path + "\" + IniFile
    If ValueStr <> "" Then
        WritePrivateProfileStringW StrPtr(SectionName), StrPtr(KeyName), StrPtr(ValueStr), StrPtr(IniFile)
    Else
        WritePrivateProfileStringW StrPtr(SectionName), StrPtr(KeyName), 0, StrPtr(IniFile)
    End If
End Sub

Public Function DumpAnsiStrToFile(ByVal str As String, ByVal FP As String) As Boolean
    Dim hFile As Long
    Dim Ret As Long
    Dim b() As Byte
    
    DumpAnsiStrToFile = False
    DeleteFileW StrPtr(FP)
    hFile = CreateFileW(StrPtr(FP), GENERIC_WRITE, FILE_SHARE_READ, 0, CREATE_NEW, FILE_FLAG_SEQUENTIAL_SCAN, 0)
    If hFile <> INVALID_HANDLE_VALUE Then
        If str <> "" Then
            b = StrConv(str, vbFromUnicode)
            WriteFile hFile, VarPtr(b(0)), UBound(b) + 1, Ret, 0
        End If
        CloseHandle hFile
        DumpAnsiStrToFile = True
    End If
    
    Erase b
End Function

Public Function IsFolderExist(ByVal FP As String) As Boolean
    Dim dwAttr As Long
    
    IsFolderExist = False
    dwAttr = GetFileAttributesW(StrPtr(FP))
    If dwAttr <> INVALID_FILE_ATTRIBUTES Then
        dwAttr = dwAttr And FILE_ATTRIBUTE_DIRECTORY
        IsFolderExist = (dwAttr <> 0)
    End If
End Function

Public Function IsFileExist(ByVal FP As String) As Boolean
    Dim dwAttr As Long
    
    IsFileExist = False
    dwAttr = GetFileAttributesW(StrPtr(FP))
    If dwAttr <> INVALID_FILE_ATTRIBUTES Then
        dwAttr = dwAttr And FILE_ATTRIBUTE_DIRECTORY
        IsFileExist = (dwAttr = 0)
    End If
End Function

Public Sub CheckAndCreateFolder(ByVal FP As String)
    If Not IsFolderExist(FP) Then
        SHCreateDirectory 0, StrPtr(FP)
    End If
End Sub

Public Function GetStrFromBinary(ByVal nLen As Long, Binary() As Byte) As String
    GetStrFromBinary = ""
    If nLen > 0 Then
        GetStrFromBinary = String$(nLen, vbNullChar)
        CopyMemory StrPtr(GetStrFromBinary), VarPtr(Binary(0)), nLen * 2
        GetStrFromBinary = Replace(GetStrFromBinary, "\..", "")
    End If
End Function

Public Sub PutStrToBinary(ByVal S As String, Binary() As Byte, nLen As Long)
    nLen = Len(S)
    If nLen > 0 Then
        CopyMemory VarPtr(Binary(0)), StrPtr(S), nLen * 2
    End If
End Sub

Public Sub CopyFile(ByVal srcFile As String, ByVal desFile As String)
    CopyFileW StrPtr(srcFile), StrPtr(desFile), 0
End Sub

Public Function ConvIpToStr(ByVal nAddr As Long) As String
    Dim b(3) As Byte
    
    CopyMemory VarPtr(b(0)), VarPtr(nAddr), 4
    ConvIpToStr = CStr(b(0)) + "." + CStr(b(1)) + "." + CStr(b(2)) + "." + CStr(b(3))
End Function

Public Function ConsoleLaunch(ByVal sExeFile As String, ByVal sExeArgs As String) As Boolean
    Dim SEI As SHELLEXECUTEINFO

    ConsoleLaunch = False
    With SEI
        .cbSize = Len(SEI)
        .fMask = SEE_MASK_FLAG_NO_UI Or SEE_MASK_NOCLOSEPROCESS Or SEE_MASK_DOENVSUBST
        .lpVerb = 0
        .lpFile = StrPtr(sExeFile)
        .lpParameters = StrPtr(sExeArgs)
        .nShow = SW_HIDE
    End With
    
    If ShellExecuteExW(SEI) <> 0 Then
        If SEI.hInstApp > 32 Then
            If SEI.hProcess <> 0 Then
                WaitForSingleObject SEI.hProcess, INFINITE
                CloseHandle SEI.hProcess
                ConsoleLaunch = True
            End If
        End If
    End If
    
End Function

Public Function GetDiskFreeSpace(ByVal DiskRoot As String) As Currency
    GetDiskFreeSpaceExW StrPtr(DiskRoot), VarPtr(GetDiskFreeSpace), 0, 0
    GetDiskFreeSpace = GetDiskFreeSpace * 10000
End Function

Public Function LoadAnsiFile(ByVal FP As String) As String
    Dim hFile As Long
    Dim nSize As Long
    Dim Ret As Long
    Dim Bin() As Byte
    
    LoadAnsiFile = ""
    hFile = CreateFileW(StrPtr(FP), GENERIC_READ, FILE_SHARE_READ, 0, OPEN_EXISTING, FILE_FLAG_SEQUENTIAL_SCAN, 0)
    If hFile <> INVALID_HANDLE_VALUE Then
        nSize = GetFileSize(hFile, Ret)
        If nSize > 0 Then
            ReDim Bin(nSize - 1)
            ReadFile hFile, VarPtr(Bin(0)), nSize, Ret, 0
            LoadAnsiFile = StrConv(Bin, vbUnicode)
        End If
        CloseHandle hFile
    End If
    
    Erase Bin
End Function

Public Function DeleteFile(ByVal srcFile As String) As Boolean
    DeleteFile = (DeleteFileW(StrPtr(srcFile)) <> 0)
End Function

Public Function ConvLongToNum(ByVal L As Long, ByVal W As Long) As String
    ConvLongToNum = Right$(String$(W, "0") + CStr(L), W)
End Function

Public Function ConvIntegerToNum(ByVal I As Integer, ByVal W As Long) As String
    ConvIntegerToNum = Right$(String$(W, "0") + CStr(I), W)
End Function

Public Function GetRandFileName(ByVal L As Long) As String
    Dim I As Long
    
    Randomize Timer
    GetRandFileName = ""
    For I = 1 To L
        GetRandFileName = GetRandFileName + CStr(CLng(Rnd * 9))
    Next
End Function

Public Function LoadIconHandle(ByVal FP As String, ByVal nSize As Long) As Long
    Dim nID As Long
    
    PrivateExtractIconsW StrPtr(FP), 0, nSize, nSize, LoadIconHandle, nID, 1, 0
End Function

Public Sub SetIconSmall(ByVal hWnd As Long, ByVal hIcon As Long)
    SendMessageW hWnd, WM_SETICON, ICON_SMALL, hIcon
End Sub

Public Sub SetIconBig(ByVal hWnd As Long, ByVal hIcon As Long)
    SendMessageW hWnd, WM_SETICON, ICON_BIG, hIcon
End Sub

Public Function GetTopParenthWnd(ByVal hWndOfForm As Long) As Long
    Dim Ret As Long
    
    Ret = GetWindowLong(hWndOfForm, GWL_HWNDPARENT)
    Do While (Ret <> 0)
        GetTopParenthWnd = Ret
        Ret = GetWindowLong(GetTopParenthWnd, GWL_HWNDPARENT)
    Loop
End Function

Public Function DumpStrToFile(ByVal str As String, ByVal FP As String) As Boolean
    Dim hFile As Long
    Dim Ret As Long
    Dim b() As Byte
    
    DumpStrToFile = False
    DeleteFileW StrPtr(FP)
    hFile = CreateFileW(StrPtr(FP), GENERIC_WRITE, FILE_SHARE_READ, 0, CREATE_NEW, FILE_FLAG_SEQUENTIAL_SCAN, 0)
    If hFile <> INVALID_HANDLE_VALUE Then
        If str <> "" Then
            b = StrConv(str, vbFromUnicode)
            WriteFile hFile, VarPtr(b(0)), UBound(b) + 1, Ret, 0
        End If
        CloseHandle hFile
        DumpStrToFile = True
    End If
    
    Erase b
End Function

Public Function DumpMemoryToFile(Bin() As Byte, ByVal BinSize As Long, ByVal FP As String) As Boolean
    Dim hFile As Long
    Dim Ret As Long
    
    DumpMemoryToFile = False
    DeleteFileW StrPtr(FP)
    hFile = CreateFileW(StrPtr(FP), GENERIC_WRITE, FILE_SHARE_READ, 0, CREATE_NEW, FILE_FLAG_SEQUENTIAL_SCAN, 0)
    If hFile <> INVALID_HANDLE_VALUE Then
        WriteFile hFile, VarPtr(Bin(0)), BinSize, Ret, 0
        CloseHandle hFile
        DumpMemoryToFile = True
    End If
End Function

Public Function ConvFileTimeToStr(ByVal lpFT As Long) As String
    Dim FT As FILETIME
    Dim ST As SYSTEMTIME
    
    FileTimeToLocalFileTime lpFT, VarPtr(FT)
    FileTimeToSystemTime VarPtr(FT), VarPtr(ST)
    With ST
        ConvFileTimeToStr = CStr(.wYear) + ConvIntegerToNum(.wMonth, 2) + ConvIntegerToNum(.wDay, 2)
        ConvFileTimeToStr = ConvFileTimeToStr + ConvIntegerToNum(.wHour, 2) + ConvIntegerToNum(.wMinute, 2) + ConvIntegerToNum(.wSecond, 2)
    End With
End Function

Public Function LoadFileToMemoryWithFileTime(ByVal FP As String, ByVal lpFT As Long, buf() As Byte, ReadSize As Long) As Boolean
    Dim hFile As Long
    Dim Ret As Long
    
    LoadFileToMemoryWithFileTime = False
    
    hFile = CreateFileW(StrPtr(FP), GENERIC_READ, FILE_SHARE_READ, 0, OPEN_EXISTING, FILE_FLAG_SEQUENTIAL_SCAN, 0)
    If hFile <> INVALID_HANDLE_VALUE Then
        ReadSize = GetFileSize(hFile, Ret)
        If ReadSize > 0 Then
            ReDim buf(ReadSize - 1)
            ReadFile hFile, VarPtr(buf(0)), ReadSize, Ret, 0
            GetFileTime hFile, 0, 0, lpFT
            
            LoadFileToMemoryWithFileTime = True
        End If
        CloseHandle hFile
    End If
End Function

Public Function ConvFileTimeToString(ByVal lpFT As Long) As String
    Dim FT As FILETIME
    Dim ST As SYSTEMTIME
    
    FileTimeToLocalFileTime lpFT, VarPtr(FT)
    FileTimeToSystemTime VarPtr(FT), VarPtr(ST)
    With ST
        ConvFileTimeToString = CStr(.wYear) + "-" + ConvIntegerToNum(.wMonth, 2) + "-" + ConvIntegerToNum(.wDay, 2)
        ConvFileTimeToString = ConvFileTimeToString + " " + ConvIntegerToNum(.wHour, 2) + ":" + ConvIntegerToNum(.wMinute, 2) + ":" + ConvIntegerToNum(.wSecond, 2)
    End With
End Function
