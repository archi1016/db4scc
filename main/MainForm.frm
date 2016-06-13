VERSION 5.00
Begin VB.Form MainForm 
   Caption         =   "Title"
   ClientHeight    =   7200
   ClientLeft      =   165
   ClientTop       =   900
   ClientWidth     =   10260
   BeginProperty Font 
      Name            =   "�L�n������"
      Size            =   12
      Charset         =   136
      Weight          =   400
      Underline       =   0   'False
      Italic          =   0   'False
      Strikethrough   =   0   'False
   EndProperty
   Icon            =   "MainForm.frx":0000
   LinkTopic       =   "Form1"
   ScaleHeight     =   480
   ScaleMode       =   3  '����
   ScaleWidth      =   684
   StartUpPosition =   3  '�t�ιw�]��
   WindowState     =   2  '�̤j��
   Begin VB.ListBox LastUploadTime 
      BeginProperty Font 
         Name            =   "�L�n������"
         Size            =   15.75
         Charset         =   136
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   5730
      Left            =   120
      TabIndex        =   2
      Top             =   960
      Width           =   10800
   End
   Begin VB.Timer TimerForCheckBackup 
      Interval        =   1000
      Left            =   9120
      Top             =   240
   End
   Begin VB.PictureBox TitleBar 
      Appearance      =   0  '����
      BackColor       =   &H8000000D&
      BorderStyle     =   0  '�S���ؽu
      ForeColor       =   &H80000008&
      Height          =   840
      Left            =   0
      ScaleHeight     =   56
      ScaleMode       =   3  '����
      ScaleWidth      =   465
      TabIndex        =   0
      Top             =   0
      Width           =   6975
      Begin VB.Line TitleLine 
         BorderColor     =   &H80000014&
         X1              =   0
         X2              =   164
         Y1              =   55
         Y2              =   55
      End
      Begin VB.Label TitleLab 
         BackStyle       =   0  '�z��
         Caption         =   "TitleLab"
         BeginProperty Font 
            Name            =   "Verdana"
            Size            =   15.75
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         ForeColor       =   &H8000000E&
         Height          =   420
         Left            =   240
         TabIndex        =   1
         Top             =   180
         Width           =   5715
      End
   End
   Begin VB.Menu menuFile 
      Caption         =   "�ɮ�(&F)"
      Begin VB.Menu menuFileExit 
         Caption         =   "�����������{�� (&X)"
      End
   End
   Begin VB.Menu menuTool 
      Caption         =   "�u��(&T)"
      Begin VB.Menu menuToolBackup 
         Caption         =   "�ƥ� (&B)"
         Shortcut        =   ^B
      End
   End
End
Attribute VB_Name = "MainForm"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = True
Attribute VB_Exposed = False
Option Explicit

Const SB_FILE_NAME = 0
Const SB_UPLOAD_INFO = 1

Dim IsCanUnload As Boolean

Dim MinsMax As Long
Dim MinsCount As Long

Dim MdbFolder As String
Dim MdbFileSize As Long
Dim LastMdbFileTime As FILETIME
Dim lpLastMdbFileTime As Long
Dim CurrentFileTime As FILETIME
Dim lpCurrentFileTime As Long

Dim SB As cStatusBar

Private Sub Form_Load()
    IsCanUnload = False
    
    Me.Caption = App.ProductName
    TitleLab.Caption = Me.Caption + " - " + App.CompanyName
    App.Title = Me.Caption
    
    MinsMax = CLng(ReadStrFromIniFile(CONFIG_INI_FILE, "CHECK_TIMER"))
    If MinsMax < 3 Then MinsMax = 3
    MinsMax = MinsMax * 60
'MinsMax = 10
    MinsCount = 0
    MdbFolder = ReadStrFromIniFile(CONFIG_INI_FILE, "MDB_FOLDER")
    
    lpLastMdbFileTime = VarPtr(LastMdbFileTime)
    lpCurrentFileTime = VarPtr(CurrentFileTime)
    
    Set SB = New cStatusBar
    With SB
        .Create2 Me.hWnd
        .SetPartsByCsvStr "240,-1"
    End With
    
    WM_TASKBARCREATED = RegisterWindowMessageW(StrPtr(TASKBAR_CREATED))
    Call ServerTrayIconAdd(Me.hWnd, Me.Icon.Handle, Me.Caption)
    OldMainFormProc = SetWindowLong(Me.hWnd, GWL_WNDPROC, AddressOf NewMainFormProc)
End Sub

Private Sub Form_Unload(Cancel As Integer)
    If IsCanUnload Then
        SetWindowLong Me.hWnd, GWL_WNDPROC, OldMainFormProc
        Call ServerTrayIconRemove(Me.hWnd)
        Set SB = Nothing
    Else
        Me.Hide
        Cancel = 1
    End If
End Sub

Private Sub Form_Resize()
    If Me.WindowState = vbMinimized Then
        Me.Hide
    Else
        TitleBar.Width = Me.ScaleWidth
        TitleLine.X2 = Me.ScaleWidth
        SB.Resize
    End If
End Sub

Public Sub WmErrorSocket(ByVal ErrorCode As Long, ByVal V As Long)
    Dim S As String
    
    Select Case ErrorCode
        Case ERROR_WSASTARTUP
            S = BuildErrorMsg("Socket���Ҫ�l�ƥ��ѡI", V)
            
        Case ERROR_CREATE_SOCKET
            S = BuildErrorMsg("�}�sSocket���ѡI", V)
            
        Case ERROR_CONNECT_REMOTE_ADDR
            S = BuildErrorMsg("�s�u�컷�ݥD�����ѡI", V)
            
        Case ERROR_SEND_DATA
            S = BuildErrorMsg("�e�X�ʥ]���ѡI", V)
            
        Case ERROR_SEND_COMPLETE
            S = "�ʥ]�ǰe�����I"
            
        Case ERROR_THE_DATA_LENGTH
            MdbFileSize = V
            S = "0 / " + FormatNumber(MdbFileSize, 0)
            
        Case ERROR_THE_DATA_OUT
            S = FormatNumber(V, 0) + " / " + FormatNumber(MdbFileSize, 0)
            
        Case ERROR_RECV_DATA
            S = BuildErrorMsg("���ݥD���S���^���ʥ]�I", V)
            
        Case ERROR_RECV_HEADER
            S = "�^�����ʥ]���Y���~�I"
            
        
        
        Case ERROR_DUMP_FILE
            S = "�g�J�ɮץ��ѡI"
        
        Case ERROR_COMPRESS_FILE
            S = "���Y�ɮץ��ѡI"
        
        Case ERROR_LOAD_FILE
            S = "���J�ɮץ��ѡI"
        
        
        
        Case ERROR_CODE_SUCCESS
            S = "�ƥ������I"
            
        Case ERROR_CODE_UNKNOW_IP
            S = "�������d�x��}�I"
            
        Case ERROR_CODE_UNKNOW_ACCOUNT
            S = "�������Τ�I"
            
        Case ERROR_CODE_UPLOAD_FAILURE
            S = "�ɮפW�ǥ��ѡI"
            
        Case ERROR_CODE_NOT_MDB
            S = "���ɦW���O.mdb�I"
        
        Case ERROR_CODE_MOVE_FAILURE
            S = "�ɮפw�W��, ���h�����ѡI"
            
        Case ERROR_CODE_ERROR_KEY
            S = "���������ҧǸ��I"
            
        Case ERROR_CODE_TIMEOUT
            S = "�ϥδ����w��I"
            
        Case ERROR_CODE_NEED_VERIFICATION
            S = "���q�L�q�l�H�c�{�ҡI"
        
        Case ERROR_CODE_UNKNOW
            S = "�������^���N�X�I"
            
        Case Else
            S = "���������~�N�X�I"
            
    End Select
    
    SB.SetPartText SB_UPLOAD_INFO, S
End Sub

Private Function BuildErrorMsg(ByVal M As String, ByVal E As Long) As String
    BuildErrorMsg = M
    If E > 0 Then
        BuildErrorMsg = BuildErrorMsg + " (���~�N�X: " + CStr(E) + ")"
    End If
End Function

Private Sub LaunchUploadProcess(ByVal lpFT As Long, ByVal FN As String)
    Dim ARGS(UPLOAD_ARGUMENT_UNBOUND) As String
    
    With LastUploadTime
        .AddItem ConvFileTimeToString(lpFT) + vbTab + FN, 0
        .ListIndex = 0
        If .ListCount >= 11 Then
            .RemoveItem .ListCount - 1
        End If
    End With
    
    ARGS(UPLOAD_ARGUMENT_API) = API_KEYWORD_UPLOAD
    ARGS(UPLOAD_ARGUMENT_HWND) = CStr(Me.hWnd)
    ARGS(UPLOAD_ARGUMENT_FILE) = FN
    
    Call ShellProgram(GetSelfExeFileName, Join(ARGS, API_SPLIT))
    
    With SB
        .SetPartText SB_FILE_NAME, FN
        .SetPartText SB_UPLOAD_INFO, ""
    End With
End Sub

Private Sub CheckFileTimeToUpload()
    Dim FN As String
    
    If GetMdbFileTime(lpCurrentFileTime, FN) Then
        If RtlCompareMemory(lpLastMdbFileTime, lpCurrentFileTime, 8) <> 8 Then
            Call LaunchUploadProcess(lpCurrentFileTime, FN)
            CopyMemory lpLastMdbFileTime, lpCurrentFileTime, 8
        End If
    End If
End Sub

Private Function GetMdbFileTime(ByVal lpFT As Long, FP As String) As Boolean
    Dim hFind As Long
    Dim WFD As WIN32_FIND_DATA
    Dim FN As String
    Dim MfInfo() As MDB_FILE_INFO
    Dim MfCount As Long
    Dim I As Long
    Dim J As Long
    Dim V As Long
    
    GetMdbFileTime = False
    
    MfCount = 0
    ReDim MfInfo(511)
    
    FP = MdbFolder + "\*.mdb"
    hFind = FindFirstFileW(StrPtr(FP), WFD)
    If hFind <> INVALID_HANDLE_VALUE Then
        Do
            WFD.dwFileAttributes = WFD.dwFileAttributes And FILE_ATTRIBUTE_DIRECTORY
            If 0 = WFD.dwFileAttributes Then
                FN = String$(MAX_PATH, vbNullChar)
                CopyMemory StrPtr(FN), VarPtr(WFD.cFileName(0)), MAX_PATH * 2
                FN = StrCutNull(FN)
                
                With MfInfo(MfCount)
                    .fName = FN
                    .fWriteTime = WFD.ftLastWriteTime
                    .lpfWriteTime = VarPtr(.fWriteTime)
                End With
                
                MfCount = MfCount + 1
            End If
        Loop Until (0 = FindNextFileW(hFind, WFD))
        FindClose hFind
    End If

    If MfCount > 0 Then
        With MfInfo(0)
            CopyMemory lpFT, .lpfWriteTime, 8
            FP = .fName
        End With
        If MfCount > 1 Then
            For I = 1 To (MfCount - 1)
                With MfInfo(I)
                    If -1 = CompareFileTime(lpFT, .lpfWriteTime) Then
                        CopyMemory lpFT, .lpfWriteTime, 8
                        FP = .fName
                    End If
                End With
            Next
        End If
        
        GetMdbFileTime = True
    End If
    
    Erase MfInfo
End Function

Private Sub menuFileExit_Click()
    IsCanUnload = True
    Unload Me
End Sub

Private Sub menuToolBackup_Click()
    Dim FN As String
    
    If GetMdbFileTime(lpCurrentFileTime, FN) Then
        Call LaunchUploadProcess(lpCurrentFileTime, FN)
    End If
End Sub

Private Sub TimerForCheckBackup_Timer()
    MinsCount = MinsCount + 1
    If MinsCount >= MinsMax Then
        Call CheckFileTimeToUpload
        MinsCount = 0
    End If
End Sub
