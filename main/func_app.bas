Attribute VB_Name = "func_app"
Option Explicit

Public Function GetProductName() As String
    GetProductName = App.ProductName
End Function

'Public Function GetProductVersion() As String
'    With App
'        GetProductVersion = ConvIntegerToNum(.Major, 4) + "." + ConvIntegerToNum(.Minor, 2) + "." + ConvIntegerToNum(.Revision, 2)
'    End With
'End Function

Public Function GetSelfExeFileName() As String
    GetSelfExeFileName = App.Path + "\" + App.EXEName + ".exe"
End Function
