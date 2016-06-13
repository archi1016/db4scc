Attribute VB_Name = "api_file"
Option Explicit

Public Const INVALID_HANDLE_VALUE = -1
Public Const INVALID_FILE_ATTRIBUTES = -1
Public Const FILE_ATTRIBUTE_TEMPORARY = &H100
Public Const FILE_ATTRIBUTE_SYSTEM = &H4
Public Const FILE_ATTRIBUTE_READONLY = &H1
Public Const FILE_ATTRIBUTE_NORMAL = &H80
Public Const FILE_ATTRIBUTE_HIDDEN = &H2
Public Const FILE_ATTRIBUTE_DIRECTORY = &H10
Public Const FILE_ATTRIBUTE_COMPRESSED = &H800
Public Const FILE_ATTRIBUTE_ARCHIVE = &H20
Public Const FILE_FLAG_SEQUENTIAL_SCAN = &H8000000
Public Const FILE_FLAG_NO_BUFFERING As Long = &H20000000
Public Const FILE_FLAG_OVERLAPPED As Long = &H40000000
Public Const FILE_FLAG_WRITE_THROUGH As Long = &H80000000
Public Const FILE_BEGIN = 0
Public Const FILE_CURRENT = 1
Public Const FILE_END = 2

Public Const GENERIC_READ = &H80000000
Public Const GENERIC_WRITE = &H40000000

Public Const FILE_SHARE_READ = &H1
Public Const FILE_SHARE_WRITE = &H2
Public Const FILE_SHARE_DELETE = &H4

Public Const CREATE_NEW = 1
Public Const CREATE_ALWAYS = 2
Public Const OPEN_ALWAYS = 4
Public Const OPEN_EXISTING = 3
Public Const TRUNCATE_EXISTING = 5

Public Declare Function RemoveDirectoryW Lib "Kernel32" _
    (ByVal lpPathName As Long) As Long
     
Public Declare Function CreateDirectoryW Lib "Kernel32" _
    (ByVal lpPathName As Long, _
     ByVal lpSecurityAttributes As Long) As Long
     
Public Declare Function CreateFileW Lib "Kernel32" _
    (ByVal lpFileName As Long, _
     ByVal dwDesiredAccess As Long, _
     ByVal dwShareMode As Long, _
     ByVal lpSecurityAttributes As Long, _
     ByVal dwCreationDisposition As Long, _
     ByVal dwFlagsAndAttributes As Long, _
     ByVal hTemplateFile As Long) As Long
     
Public Declare Function WriteFile Lib "Kernel32" _
    (ByVal hFile As Long, _
     ByVal lpBuffer As Long, _
     ByVal nNumberOfBytesToWrite As Long, _
     lpNumberOfBytesWritten As Long, _
     ByVal lpOverlapped As Long) As Long
     
Public Declare Function ReadFile Lib "Kernel32" _
    (ByVal hFile As Long, _
     ByVal lpBuffer As Long, _
     ByVal nNumberOfBytesToRead As Long, _
     lpNumberOfBytesRead As Long, _
     ByVal lpOverlapped As Long) As Long
     
Public Declare Function GetFileSize Lib "Kernel32" _
    (ByVal hFile As Long, _
     lpFileSizeHigh As Long) As Long

Public Declare Function GetFileSizeEx Lib "Kernel32" _
    (ByVal hFile As Long, _
     lpFileSize As Currency) As Long
     
Public Declare Function GetFileAttributesW Lib "Kernel32" _
    (ByVal lpFileName As Long) As Long
    
Public Declare Function SetFileAttributesW Lib "Kernel32" _
    (ByVal lpFileName As Long, _
     ByVal dwFileAttributes As Long) As Long

Public Declare Function GetFileTime Lib "Kernel32" _
    (ByVal hFile As Long, _
     ByVal lpCreationTime As Long, _
     ByVal lpLastAccessTime As Long, _
     ByVal lpLastWriteTime As Long) As Long
     
Public Declare Function SetFileTime Lib "Kernel32" _
    (ByVal hFile As Long, _
     ByVal lpCreationTime As Long, _
     ByVal lpLastAccessTime As Long, _
     ByVal lpLastWriteTime As Long) As Long
    
Public Declare Function SetEndOfFile Lib "Kernel32" _
    (ByVal hFile As Long) As Long
    
Public Declare Function SetFilePointer Lib "Kernel32" _
    (ByVal hFile As Long, _
     ByVal liDistanceToMove As Long, _
     ByVal lpDistanceToMoveHigh As Long, _
     ByVal dwMoveMethod As Long) As Long
     
Public Declare Function SetFilePointerEx Lib "Kernel32" _
    (ByVal hFile As Long, _
     ByVal liDistanceToMove As Currency, _
     lpNewFilePointer As Currency, _
     ByVal dwMoveMethod As Long) As Long

Public Declare Function DeleteFileW Lib "Kernel32" _
    (ByVal lpFileName As Long) As Long
    
Public Declare Function CopyFileW Lib "Kernel32" _
    (ByVal lpExistingFileName As Long, _
     ByVal lpNewFileName As Long, _
     ByVal bFailIfExists As Long) As Long
    
Public Declare Function MoveFileW Lib "Kernel32" _
    (ByVal lpExistingFileName As Long, _
     ByVal lpNewFileName As Long) As Long

Public Declare Function FindFirstFileW Lib "Kernel32" _
    (ByVal lpFileName As Long, _
     lpFindFileData As WIN32_FIND_DATA) As Long
     
Public Declare Function FindNextFileW Lib "Kernel32" _
    (ByVal hFindFile As Long, _
     lpFindFileData As WIN32_FIND_DATA) As Long
     
Public Declare Function FindClose Lib "Kernel32" _
    (ByVal hFindFile As Long) As Long

Public Type FILETIME
    dwLowDateTime As Long
    dwHighDateTime As Long
End Type

Public Type WIN32_FIND_DATA
    dwFileAttributes As Long
    ftCreationTime As FILETIME
    ftLastAccessTime As FILETIME
    ftLastWriteTime As FILETIME
    nFileSizeHigh As Long
    nFileSizeLow As Long
    dwReserved0 As Long
    dwReserved1 As Long
    cFileName(519) As Byte
    cAlternate(27) As Byte
End Type

Public Declare Function GetLogicalDrives Lib "Kernel32" () As Long


Public Declare Function SetFileValidData Lib "Kernel32" _
    (ByVal hFile As Long, _
     ByVal ValidDataLength As Currency) As Long

