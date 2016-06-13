Attribute VB_Name = "cmd"
Option Explicit

Sub Main()
    Call InitCommonControls
    If "" = Command Then
        If App.PrevInstance Then
            Call MsgError("½Ð¤Å­«ÂÐ°õ¦æ """ + App.ProductName + """ ¡I")
        Else
            MainForm.Show
        End If
    Else
        Call DecodeCommand
    End If
End Sub

Private Sub DecodeCommand()
    Dim A() As String
    
    A = Split(Command, API_SPLIT)
    Select Case A(0)
        Case API_KEYWORD_UPLOAD
            Call LaunchUpload(CLng(A(UPLOAD_ARGUMENT_HWND)), A)
        
    End Select
    
    Erase A
End Sub

Private Sub LaunchUpload(ByVal hWnd As Long, A() As String)
    Dim FP As String
    Dim Bin() As Byte
    Dim BinSize As Long
    Dim FT As FILETIME
    Dim FTS As String
    Dim WD As WSAData
    Dim hSocket As Long
    Dim ServerSA As SOCKADDR
    Dim Key(19) As Byte
    Dim TempMdb As String
    Dim Temp7z As String
    
    If 0 = WSAStartup(&H202, WD) Then
        hSocket = Socket(AF_INET, SOCK_STREAM, IPPROTO_TCP)
        If INVALID_SOCKET <> hSocket Then
            FP = ReadStrFromIniFile(CONFIG_INI_FILE, "MDB_FOLDER") + "\" + A(UPLOAD_ARGUMENT_FILE)
            If LoadFileToMemoryWithFileTime(FP, VarPtr(FT), Bin, BinSize) Then
                FTS = ConvFileTimeToStr(VarPtr(FT))
                HashStringToSHAbin App.CompanyName, Key
                
                TempMdb = App.Path + "\" + FTS + "-DBNet.mdb"
                Temp7z = App.Path + "\temp.7z"
                
                If DumpMemoryToFile(Bin, BinSize, TempMdb) Then
                    If ConsoleLaunch(App.Path + "\7z.exe", "a """ + Temp7z + """ -p" + DecodeCodeToString(PASSWORD_FOR_7Z, Key, 20) + " """ + TempMdb + """") Then
                        If LoadFileToMemory(Temp7z, Bin, BinSize) Then
                            With ServerSA
                                .sin_family = AF_INET
                                .sin_addr.S_addr = inet_addr(DecodeCodeToString(REMOTE_SERVICE_ADDR, Key, 20))
                                .sin_port = &H5000
                            End With
                            If SOCKET_ERROR <> connect(hSocket, ServerSA, Len(ServerSA)) Then
                                Call UploadProcess(CLng(A(UPLOAD_ARGUMENT_HWND)), hSocket, FTS, VarPtr(Bin(0)), BinSize, Key)
                                shutdown hSocket, SD_BOTH
                            Else
                                Call MsgErrorSocket(hWnd, ERROR_CONNECT_REMOTE_ADDR)
                            End If
                        Else
                            Call MsgErrorSocket(hWnd, ERROR_LOAD_FILE)
                        End If
                        DeleteFile Temp7z
                    Else
                        Call MsgErrorSocket(hWnd, ERROR_COMPRESS_FILE)
                    End If
                    DeleteFile TempMdb
                Else
                    Call MsgErrorSocket(hWnd, ERROR_DUMP_FILE)
                End If
            Else
                Call MsgErrorSocket(hWnd, ERROR_LOAD_FILE)
            End If
            closesocket hSocket
        Else
            Call MsgErrorSocket(hWnd, ERROR_CREATE_SOCKET)
        End If
        
        WSACleanup
    Else
        Call MsgErrorSocket(hWnd, ERROR_WSASTARTUP)
    End If
    
    Erase Bin
End Sub

Private Sub UploadProcess(ByVal hWnd As Long, ByVal hSocket As Long, ByVal FTS As String, ByVal lpBin As Long, ByVal BinSize As Long, Key() As Byte)
    Dim Boundary As String
    Dim H As String
    Dim b As String
    Dim Offset As Long
    Dim BodyBin() As Byte
    Dim BodyBinSize As Long
    Dim lpBodyBin As Long
    Dim HeaderBin() As Byte
    Dim HeaderBinSize As Long
    Dim lpHeaderBin As Long
    Dim SendBin() As Byte
    Dim SendBinSize As Long
    Dim lpSendBin As Long
    Dim CountDown As Long
    Dim BlockSize As Long
    Dim TransCount As Long
    Dim lpBuf As Long
    Dim Ret As Long
    Dim R() As String
    
    Boundary = "db4scc-" + GetRandFileName(16) + "-db4scc"

    b = BuildPostFiled(Boundary, "fsize", CStr(BinSize))
    b = b + BuildPostFiled(Boundary, "ftime", FTS)
    b = b + "--" + Boundary + vbCrLf
    b = b + "Content-Disposition: form-data; name=""fmdb""; filename=""" + GetRandFileName(8) + ".mdb""" + vbCrLf
    b = b + "Content-Type: application/octet-stream" + vbCrLf
    b = b + vbCrLf
    b = b + String$(BinSize, vbNullChar) + vbCrLf
    b = b + "--" + Boundary + "--" + vbCrLf
    
    Offset = InStr(b, vbNullChar) - 1
    
    BodyBin = StrConv(b, vbFromUnicode)
    lpBodyBin = VarPtr(BodyBin(0))
    BodyBinSize = UBound(BodyBin) + 1
    
    CopyMemory lpBodyBin + Offset, lpBin, BinSize
    
    
    H = "POST " + DecodeCodeToString(REMOTE_SERVICE_URL, Key, 20) + " HTTP/1.1" + vbCrLf
    H = H + "Host: " + DecodeCodeToString(REMOTE_SERVICE_ADDR, Key, 20) + vbCrLf
    H = H + "Content-Length: " + CStr(BodyBinSize) + vbCrLf
    H = H + "User-Agent: " + DecodeCodeToString(UPLOAD_USER_AGENT, Key, 20) + vbCrLf
    H = H + "Content-Type: multipart/form-data; boundary=" + Boundary + vbCrLf
    H = H + "Accept: text/html" + vbCrLf
    H = H + vbCrLf
    
    HeaderBin = StrConv(H, vbFromUnicode)
    lpHeaderBin = VarPtr(HeaderBin(0))
    HeaderBinSize = UBound(HeaderBin) + 1
    
    SendBinSize = HeaderBinSize + BodyBinSize
    ReDim SendBin(SendBinSize - 1)
    lpSendBin = VarPtr(SendBin(0))
    CopyMemory lpSendBin, lpHeaderBin, HeaderBinSize
    CopyMemory lpSendBin + HeaderBinSize, lpBodyBin, BodyBinSize
    Call MsgErrorSocketEx(hWnd, ERROR_THE_DATA_LENGTH, SendBinSize)

    CountDown = SendBinSize
    lpBuf = lpSendBin
    TransCount = 0
    Do While (CountDown > 0)
        If CountDown >= 8192 Then
            BlockSize = 8192
        Else
            BlockSize = CountDown
        End If
        TransCount = TransCount + BlockSize
        Call MsgErrorSocketEx(hWnd, ERROR_THE_DATA_OUT, TransCount)
        
        Ret = send(hSocket, lpBuf, BlockSize, 0)
        If SOCKET_ERROR <> Ret Then
            lpBuf = lpBuf + Ret
            CountDown = CountDown - Ret
        Else
            Call MsgErrorSocket(hWnd, ERROR_SEND_DATA)
            Exit Do
        End If
    Loop
    If 0 = CountDown Then
        Call MsgErrorSocket(hWnd, ERROR_SEND_COMPLETE)
        Ret = recv(hSocket, lpSendBin, SendBinSize, 0)
        If SOCKET_ERROR <> Ret Then
            ReDim HeaderBin(Ret - 1)
            CopyMemory VarPtr(HeaderBin(0)), lpSendBin, Ret
            H = StrConv(HeaderBin, vbUnicode)
            If "HTTP/1.1 200" = Left$(H, 12) Then
                R = Split(H, vbCrLf + vbCrLf)
                If UBound(R) >= 1 Then
                    Call MsgErrorSocket(hWnd, ConvReturnErrorCode(R(1)))
                Else
                    Call MsgErrorSocket(hWnd, ERROR_RECV_HEADER)
                End If
            Else
                Call MsgErrorSocket(hWnd, ERROR_RECV_HEADER)
            End If
        Else
            Call MsgErrorSocket(hWnd, ERROR_RECV_DATA)
        End If
    End If
    
    Erase BodyBin
    Erase HeaderBin
    Erase SendBin
End Sub

Private Function BuildPostFiled(ByVal Boundary As String, ByVal FiledName As String, ByVal TheValue As String) As String
    BuildPostFiled = "--" + Boundary + vbCrLf
    BuildPostFiled = BuildPostFiled + "Content-Disposition: form-data; name=""" + FiledName + """" + vbCrLf
    BuildPostFiled = BuildPostFiled + vbCrLf
    BuildPostFiled = BuildPostFiled + TheValue + vbCrLf
End Function

Private Sub MsgErrorSocket(ByVal hWnd As Long, ByVal ErrCode As Long)
    SendMessageW hWnd, WM_ERROR_SOCKET, ErrCode, Err.LastDllError
End Sub

Private Sub MsgErrorSocketEx(ByVal hWnd As Long, ByVal ErrCode As Long, ByVal nData As Long)
    SendMessageW hWnd, WM_ERROR_SOCKET, ErrCode, nData
End Sub

Private Function ConvReturnErrorCode(ByVal R As String) As Long
    Dim E As Long
    
    Select Case R
        Case HTTP_RETURN_CODE_SUCCESS
            E = ERROR_CODE_SUCCESS
            
        Case HTTP_RETURN_CODE_UNKNOW_IP
            E = ERROR_CODE_UNKNOW_IP
            
        Case HTTP_RETURN_CODE_UNKNOW_ACCOUNT
            E = ERROR_CODE_UNKNOW_ACCOUNT
            
        Case HTTP_RETURN_CODE_UPLOAD_FAILURE
            E = ERROR_CODE_UPLOAD_FAILURE
            
        Case HTTP_RETURN_CODE_NOT_MDB
            E = ERROR_CODE_NOT_MDB
            
        Case HTTP_RETURN_CODE_MOVE_FAILURE
            E = ERROR_CODE_MOVE_FAILURE
            
        Case HTTP_RETURN_CODE_ERROR_KEY
            E = ERROR_CODE_ERROR_KEY
            
        Case HTTP_RETURN_CODE_TIMEOUT
            E = ERROR_CODE_TIMEOUT
            
        Case HTTP_RETURN_CODE_NEED_VERIFICATION
            E = ERROR_CODE_NEED_VERIFICATION
        
        Case Else
            E = ERROR_CODE_UNKNOW
    End Select
    
    ConvReturnErrorCode = E
End Function
