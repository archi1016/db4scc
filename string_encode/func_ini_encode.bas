Attribute VB_Name = "func_ini_encode"
Option Explicit

Private Const EncodeSpliteChars = 14

Public Function EncodeStringToCode(ByVal SrcStr As String, Key() As Byte, ByVal KeyLen As Long) As String
    Dim I As Long
    Dim J As Long
    Dim TotalBlock As Long
    Dim cc(1) As Long
    Dim EE(1) As Long
    Dim N As String
    Dim b As Byte
    Dim SB() As Byte
    Dim DA() As Currency
    Dim CR() As String
    
    EE(0) = 0
    EE(1) = Len(SrcStr)
    I = EE(1) * 2
    ReDim SB(I - 1)
    CopyMemory VarPtr(SB(0)), StrPtr(SrcStr), I
    
    J = 0
    CopyMemory VarPtr(J), VarPtr(SB(0)), 1
    For I = 1 To UBound(SB)
        J = J + SB(I)
    Next
    CopyMemory VarPtr(b), VarPtr(J), 1
    N = vbNullString
    For I = UBound(SB) To 0 Step -1
        J = 0
        CopyMemory VarPtr(J), VarPtr(SB(I)), 1
        J = J + I * 421
        CopyMemory VarPtr(SB(I)), VarPtr(J), 1
        N = N + ByteToNumber(SB(I) Xor b)
    Next
    N = ByteToNumber(b) + N
    
    N = StrReverse(N)
    I = Len(N) Mod EncodeSpliteChars
    If I > 0 Then
        EE(0) = EncodeSpliteChars - I
        N = String$(EE(0), "0") + N
    End If
    
    TotalBlock = Len(N) \ EncodeSpliteChars
    ReDim DA(TotalBlock - 1)
    
    For I = 0 To (TotalBlock - 1)
        DA(I) = CCur("7" + Mid$(N, I * EncodeSpliteChars + 1, EncodeSpliteChars))
        CopyMemory VarPtr(cc(0)), VarPtr(DA(I)), 8
        cc(1) = cc(1) Xor cc(0)
        CopyMemory VarPtr(DA(I)), VarPtr(cc(0)), 8
    Next
    
    ReDim SB(TotalBlock * 8 + 7) '8*8+4*2
    EE(1) = Not (EE(1) Xor EE(0))
    EE(0) = Not EE(0)
    CopyMemory VarPtr(SB(0)), VarPtr(DA(0)), TotalBlock * 8
    CopyMemory VarPtr(SB(TotalBlock * 8)), VarPtr(EE(0)), 8

    N = vbNullString
    For I = 0 To UBound(SB)
        N = N + ByteToBinary(SB(I))
    Next
    
    I = Len(N) Mod 6
    If I > 0 Then
        N = N + String$(6 - I, "0")
    End If

    J = Len(N) \ 6
    ReDim SB(J - 1)
    For I = 0 To UBound(SB)
        SB(I) = BinaryToByte(Mid$(N, I * 6 + 1, 6))
        SB(I) = SB(I) Xor Key(I Mod KeyLen)
    Next

    Call GetRandString(CR)
    N = vbNullString
    For I = 0 To UBound(SB)
        J = 0
        CopyMemory VarPtr(J), VarPtr(SB(I)), 1
        J = (J + I * 953) And &H3F
        N = N + CR(J)
    Next

    EncodeStringToCode = Join(CR, "")
    EncodeStringToCode = StrReverse(Right$(EncodeStringToCode, 28)) + N + StrReverse(Left$(EncodeStringToCode, 36))
    
    Erase SB
    Erase DA
    Erase CR
End Function

Private Function ByteToNumber(ByVal b As Byte) As String
    Dim V As Long
    Dim S As String
    
    V = 0
    b = Not b
    CopyMemory VarPtr(V), VarPtr(b), 1
    S = CStr(V)
    
    Select Case Len(S)
        Case 1:
            S = "00" + S
        Case 2:
            S = "0" + S
    End Select
    
    ByteToNumber = S
End Function

Private Function ByteToBinary(b As Byte) As String
    Dim I As Long
    
    ByteToBinary = vbNullString
    For I = 0 To 7
        If (b And (2 ^ I)) = 0 Then
            ByteToBinary = ByteToBinary + "0"
        Else
            ByteToBinary = ByteToBinary + "1"
        End If
    Next
End Function

Private Function BinaryToByte(ByVal S As String) As Byte
    Dim I As Long
    
    BinaryToByte = 0
    For I = 1 To 6
        If Mid$(S, I, 1) = "1" Then BinaryToByte = BinaryToByte Or 2 ^ (I - 1)
    Next
End Function

Private Sub GetRandString(CR() As String)
    Dim a As Long
    Dim b As Long
    Dim J As Long
    Dim S As String
    
    CR = Split("*,@,[,],Q,:,F,j,T,3,W,/,{,},<,>,_,6,9,!,$,%,^,\,X,;,C,D,?,~,5,P,x,y,#,k,m,',p,q,r,s,t,G,2,J,K,M,i,`,b,c,d,4,f,g,h,7,w,&,(,),+,-", ",")
    For J = 0 To &H4FF
        Randomize Timer
        a = CLng(Rnd * 63)
        Randomize Timer
        b = CLng(Rnd * 63)
        S = CR(a)
        CR(a) = CR(b)
        CR(b) = S
    Next
End Sub
