VERSION 5.00
Begin VB.Form MainForm 
   BorderStyle     =   1  '��u�T�w
   ClientHeight    =   7920
   ClientLeft      =   45
   ClientTop       =   435
   ClientWidth     =   10800
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
   MaxButton       =   0   'False
   ScaleHeight     =   528
   ScaleMode       =   3  '����
   ScaleWidth      =   720
   StartUpPosition =   2  '�ù�����
   Begin VB.CommandButton zwDecode 
      Caption         =   "�ѽX (&D)"
      BeginProperty Font 
         Name            =   "�L�n������"
         Size            =   14.25
         Charset         =   136
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   600
      Left            =   8760
      TabIndex        =   10
      Top             =   4080
      Width           =   1800
   End
   Begin VB.TextBox xxxText 
      BeginProperty Font 
         Name            =   "�L�n������"
         Size            =   14.25
         Charset         =   136
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   480
      Left            =   2100
      TabIndex        =   7
      Top             =   2880
      Width           =   8460
   End
   Begin VB.TextBox realText 
      BeginProperty Font 
         Name            =   "�L�n������"
         Size            =   14.25
         Charset         =   136
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   480
      Left            =   2100
      Locked          =   -1  'True
      TabIndex        =   6
      Top             =   3480
      Width           =   8460
   End
   Begin VB.CommandButton zwCopy 
      Caption         =   "�ƻs (&P)"
      BeginProperty Font 
         Name            =   "�L�n������"
         Size            =   14.25
         Charset         =   136
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   600
      Left            =   8760
      TabIndex        =   5
      Top             =   1680
      Width           =   1800
   End
   Begin VB.CommandButton zwEncode 
      Caption         =   "�[�K (&C)"
      BeginProperty Font 
         Name            =   "�L�n������"
         Size            =   14.25
         Charset         =   136
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   600
      Left            =   6840
      TabIndex        =   4
      Top             =   1680
      Width           =   1800
   End
   Begin VB.TextBox toText 
      BeginProperty Font 
         Name            =   "�L�n������"
         Size            =   14.25
         Charset         =   136
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   480
      Left            =   2100
      Locked          =   -1  'True
      TabIndex        =   3
      Top             =   1080
      Width           =   8460
   End
   Begin VB.TextBox srcText 
      BeginProperty Font 
         Name            =   "�L�n������"
         Size            =   14.25
         Charset         =   136
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   480
      Left            =   2100
      TabIndex        =   1
      Top             =   480
      Width           =   8460
   End
   Begin VB.Label Label1 
      AutoSize        =   -1  'True
      BackStyle       =   0  '�z��
      Caption         =   "�[�K�r�� (&3):"
      Height          =   300
      Index           =   3
      Left            =   240
      TabIndex        =   9
      Top             =   2940
      Width           =   1365
   End
   Begin VB.Label Label1 
      AutoSize        =   -1  'True
      BackStyle       =   0  '�z��
      Caption         =   "�٭�� (&4):"
      Height          =   300
      Index           =   2
      Left            =   240
      TabIndex        =   8
      Top             =   3540
      Width           =   1125
   End
   Begin VB.Label Label1 
      AutoSize        =   -1  'True
      BackStyle       =   0  '�z��
      Caption         =   "�[�K�� (&2):"
      Height          =   300
      Index           =   1
      Left            =   240
      TabIndex        =   2
      Top             =   1140
      Width           =   1125
   End
   Begin VB.Label Label1 
      AutoSize        =   -1  'True
      BackStyle       =   0  '�z��
      Caption         =   "��l�r�� (&1):"
      Height          =   300
      Index           =   0
      Left            =   240
      TabIndex        =   0
      Top             =   540
      Width           =   1365
   End
End
Attribute VB_Name = "MainForm"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = True
Attribute VB_Exposed = False
Option Explicit

Private Sub Form_Load()
    Me.Caption = App.ProductName
    App.Title = Me.Caption
End Sub

Private Sub zwCopy_Click()
    With Clipboard
        .Clear
        .SetText toText.Text
    End With
End Sub

Private Sub zwDecode_Click()
    Dim Key(19) As Byte
    
    If xxxText.Text <> "" Then
        HashStringToSHAbin App.CompanyName, Key
        realText.Text = DecodeCodeToString(xxxText.Text, Key, 20)
    Else
        Call MsgError("�п�J�[�K�r��I")
        xxxText.SetFocus
    End If
End Sub

Private Sub zwEncode_Click()
    Dim Key(19) As Byte
    
    If srcText.Text <> "" Then
        HashStringToSHAbin App.CompanyName, Key
        toText.Text = EncodeStringToCode(srcText.Text, Key, 20)
    Else
        Call MsgError("�п�J��l�r��I")
        srcText.SetFocus
    End If
End Sub
