Attribute VB_Name = "func_ini_decode"
Option Explicit

Private Const EncodeSpliteChars = 14

Public Function DecodeCodeToString(ByVal SrcCode As String, Key() As Byte, ByVal KeyLen As Long) As String
    Dim I As Long
    Dim J As Long
    Dim K As Long
    Dim TotalBlock As Long
    Dim cc(1) As Long
    Dim EE(1) As Long
    Dim T As String
    Dim N As String
    Dim b As Byte
    Dim SB() As Byte
    Dim DA() As Currency
    Dim CR As String
    
    CR = StrReverse(Right$(SrcCode, 36)) + StrReverse(Left$(SrcCode, 28))

    N = Mid$(SrcCode, 29, Len(SrcCode) - 64)
    J = Len(N)
    ReDim SB(J - 1)
    For I = 0 To UBound(SB)
        SB(I) = InStr(CR, Mid$(N, I + 1, 1)) - 1
        CopyMemory VarPtr(J), VarPtr(SB(I)), 1
        J = J Or &H7FFFC0
        J = (J - I * 953) And &H3F
        CopyMemory VarPtr(SB(I)), VarPtr(J), 1
    Next I

    N = vbNullString
    For I = 0 To UBound(SB)
        SB(I) = SB(I) Xor Key(I Mod KeyLen)
        N = N + Left$(ByteToBinary(SB(I)), 6)
    Next

    I = Len(N)
    J = I Mod 8
    If J > 0 Then N = Left$(N, I - J)

    J = Len(N) \ 8
    ReDim SB(J - 1)
    For I = 0 To (J - 1)
        SB(I) = BinaryToByte2(Mid$(N, I * 8 + 1, 8))
    Next

    
    TotalBlock = ((UBound(SB) + 1) \ 8) - 1
    ReDim DA(TotalBlock - 1)
    CopyMemory VarPtr(DA(0)), VarPtr(SB(0)), TotalBlock * 8
    CopyMemory VarPtr(EE(0)), VarPtr(SB(TotalBlock * 8)), 8
    EE(0) = Not EE(0)
    EE(1) = Not (EE(1) Xor EE(0))

    N = ""
    For I = 0 To (TotalBlock - 1)
        CopyMemory VarPtr(cc(0)), VarPtr(DA(I)), 8
        cc(1) = cc(1) Xor cc(0)
        CopyMemory VarPtr(DA(I)), VarPtr(cc(0)), 8
        T = CStr(DA(I))
        T = Right$(T, EncodeSpliteChars)
        N = N + T
    Next
    If EE(0) > 0 Then N = Right$(N, Len(N) - EE(0))
    N = StrReverse(N)
    
    T = String$(EE(1), " ")
    TotalBlock = EE(1) * 2
    ReDim SB(TotalBlock - 1)
    b = CByte(Left$(N, 3))
    J = 1
    For I = (TotalBlock - 1) To 0 Step -1
        SB(I) = CByte(Mid$(N, J * 3 + 1, 3)) Xor b
        K = &H7FFFF00
        CopyMemory VarPtr(K), VarPtr(SB(I)), 1
        K = K - I * 421
        CopyMemory VarPtr(SB(I)), VarPtr(K), 1
        J = J + 1
    Next
    CopyMemory StrPtr(T), VarPtr(SB(0)), TotalBlock
    DecodeCodeToString = T
    
    Erase SB
    Erase DA
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

Private Function BinaryToByte2(ByVal S As String) As Byte
    Dim I As Long
    
    BinaryToByte2 = 0
    For I = 1 To 8
        If Mid$(S, I, 1) = "1" Then BinaryToByte2 = BinaryToByte2 Or 2 ^ (I - 1)
    Next
End Function
