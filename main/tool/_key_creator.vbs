dim FSO
dim Chars
dim CharsU
dim i
dim j
dim k
dim r1
dim r2
dim c
dim Keys
dim PlusKeys
dim PlusDays

PlusKeys = 30
PlusDays = "30"



private sub RndChars()
	Randomize
	for i = 1 to 256
		r1 = clng(rnd * CharsU)
		r2 = clng(rnd * CharsU)
		c = Chars(r1)
		Chars(r1) = Chars(r2)
		Chars(r2) = c
	next
end sub

private function GetRndStr(l)
	GetRndStr = ""
	for k = 1 to l
		GetRndStr = GetRndStr + Chars(clng(rnd * CharsU))
	next
end function

Set FSO = CreateObject("Scripting.FileSystemObject")
Chars = split("3,4,5,6,7,8,9,A,B,C,D,E,F,G,H,J,K,L,M,N,P,Q,R,S,T,U,V,W,X,Y", ",")
CharsU = ubound(Chars)
redim Keys(PlusKeys-1)

for j = 0 to (PlusKeys-1)
	call RndChars
	Keys(j) = GetRndStr(6) + "-" + GetRndStr(4) + "-" + GetRndStr(6) + "-" + GetRndStr(4) + "-" + GetRndStr(6)
	
	set Fw = Fso.OpenTextFile(Keys(j)+".txt", 2, True, 0)
	Fw.Write PlusDays
	Fw.Close
	
	Keys(j) = Keys(j) + vbTab + PlusDays + vbTab + "unused"
next

set Fw = Fso.OpenTextFile("_KEY_LIST.txt", 2, True, 0)
Fw.Write join(Keys, vbCrLf)
Fw.Close


'set Fw = Fso.OpenTextFile(IniFile, 2, True, 0)
'Fw.Write T
'Fw.Close
