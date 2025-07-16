# PowerShell Script: Test-NetworkPorts-Interactive-Enhanced.ps1

# Ask for the target IP or hostname
$TargetIP = Read-Host "Enter the IP address or hostname to test"

# Define the list of ports and protocols
$ports = @(
    @{
        Port = 6661; Protocol = "TCP"; Service = "Mirth Connect"; Description = "Mirth® Connect third-party software by NextGen Healthcare use for HL7 integration."
        },
    @{
        Port = 2200; Protocol = "TCP"; Service = "Natus Database (XLDB)"; Description = "The Natus Database (XLDB) is a Natus application that provides a distributed database view of live, completed, and archived EEG/PSG patient studies. It synchronizes data with local MSSQL Express instance and central MSQL Server, as well as other remote MSSQL Express instances running on computers with Natus EEG/PSG application software. It also uses a UDP communication channel to discover Natus proprietary IP-based hardware devices like the Natus Base Unit and other amplifiers"
    },
    @{
        Port = 2200; Protocol = "UDP"; Service = "Natus Database (XLDB)"; Description = "The Natus Database (XLDB) is a Natus application that provides a distributed database view of live, completed, and archived EEG/PSG patient studies. It synchronizes data with local MSSQL Express instance and central MSQL Server, as well as other remote MSSQL Express instances running on computers with Natus EEG/PSG application software. It also uses a UDP communication channel to discover Natus proprietary IP-based hardware devices like the Natus Base Unit and other amplifiers"
    },
    @{
        Port = 5624; Protocol = "TCP"; Service = "Natus Database (XLDB)"; Description = "The Natus Database (XLDB) is a Natus application that provides a distributed database view of live, completed, and archived EEG/PSG patient studies. It synchronizes data with local MSSQL Express instance and central MSQL Server, as well as other remote MSSQL Express instances running on computers with Natus EEG/PSG application software. It also uses a UDP communication channel to discover Natus proprietary IP-based hardware devices like the Natus Base Unit and other amplifiers"
    },
    @{
        Port = 5624; Protocol = "UDP"; Service = "Natus Database (XLDB)"; Description = "The Natus Database (XLDB) is a Natus application that provides a distributed database view of live, completed, and archived EEG/PSG patient studies. It synchronizes data with local MSSQL Express instance and central MSQL Server, as well as other remote MSSQL Express instances running on computers with Natus EEG/PSG application software. It also uses a UDP communication channel to discover Natus proprietary IP-based hardware devices like the Natus Base Unit and other amplifiers"
    },
    @{
        Port = 5625; Protocol = "TCP"; Service = "Natus Database (XLDB)"; Description = "The Natus Database (XLDB) is a Natus application that provides a distributed database view of live, completed, and archived EEG/PSG patient studies. It synchronizes data with local MSSQL Express instance and central MSQL Server, as well as other remote MSSQL Express instances running on computers with Natus EEG/PSG application software. It also uses a UDP communication channel to discover Natus proprietary IP-based hardware devices like the Natus Base Unit and other amplifiers"
    },
    @{
        Port = 5625; Protocol = "UDP"; Service = "Natus Database (XLDB)"; Description = "The Natus Database (XLDB) is a Natus application that provides a distributed database view of live, completed, and archived EEG/PSG patient studies. It synchronizes data with local MSSQL Express instance and central MSQL Server, as well as other remote MSSQL Express instances running on computers with Natus EEG/PSG application software. It also uses a UDP communication channel to discover Natus proprietary IP-based hardware devices like the Natus Base Unit and other amplifiers"
    },
    @{
        Port = 443; Protocol = "TCP"; Service = "Natus Gateway Service API"; Description = "Implemented in NW10GMA2, this service can be configured to listen for autoSCORE license and usage information from other Neuroworks systems on this port."
    },
    @{
        Port = 5000; Protocol = "TCP"; Service = "Natus Licensing Service API"; Description = "Implemented in NW10GMA2, this service receives local autoSCORE license and usage information from the local Neuroworks application on this port. (REST calls based on JSON encapsulated in http/https requests)."
    },
    @{
        Port = 2020; Protocol = "TCP"; Service = "NWChat"; Description = "The NWChat service for short text messages using the Wave application via the `"lips`" icon in the top right corner of the screen. ."
    },
    @{
        Port = 2021; Protocol = "TCP"; Service = "NWChat"; Description = "The NWChat service for short text messages using the Wave application via the `"lips`" icon in the top right corner of the screen. ."
    },
    @{
        Port = 5554; Protocol = "TCP"; Service = "NWDiscoveryApp"; Description = "Implemented in NW10GMA4, the Discovery app is deployed on Monitoring station. It uses port 5554 for AUTH requests, and port 5555 to send out active list info. It implements following functionalities:
  â€¢ Obtain the list of machines that are monitored by current station
  â€¢ Retrieve the study info and patient info of the live study from the monitored stations
  â€¢ Serialize the information for transmitting via network
  â€¢ Work as a server which provides the info to the requesters that connect"
    },
    @{
        Port = 5555; Protocol = "TCP"; Service = "NWDiscoveryApp"; Description = "Implemented in NW10GMA4, the Discovery app is deployed on Monitoring station. It uses port 5554 for AUTH requests, and port 5555 to send out active list info. It implements following functionalities:
  â€¢ Obtain the list of machines that are monitored by current station
  â€¢ Retrieve the study info and patient info of the live study from the monitored stations
  â€¢ Serialize the information for transmitting via network
  â€¢ Work as a server which provides the info to the requesters that connect"
    },
    @{
        Port = 5595; Protocol = "TCP"; Service = "NWGateway"; Description = "Implemented in NW10GMA4, the NWGateway is a service that is deployed on acquisition station. NWGateway can host multiple proxies to communicate with different clients. CNS proxy implemented in NW10GMA4 enables data flow between non-NW client like the Mobery CNS and NW components. It uses port 5711 and 5596 to communicate internally with Neuroworks, and ports 5595, 5597, 5598, and 5599 to communicate externally with Moberg CNS monitors."
    },
    @{
        Port = 5596; Protocol = "TCP"; Service = "NWGateway"; Description = "Implemented in NW10GMA4, the NWGateway is a service that is deployed on acquisition station. NWGateway can host multiple proxies to communicate with different clients. CNS proxy implemented in NW10GMA4 enables data flow between non-NW client like the Mobery CNS and NW components. It uses port 5711 and 5596 to communicate internally with Neuroworks, and ports 5595, 5597, 5598, and 5599 to communicate externally with Moberg CNS monitors."
    },
    @{
        Port = 5597; Protocol = "TCP"; Service = "NWGateway"; Description = "Implemented in NW10GMA4, the NWGateway is a service that is deployed on acquisition station. NWGateway can host multiple proxies to communicate with different clients. CNS proxy implemented in NW10GMA4 enables data flow between non-NW client like the Mobery CNS and NW components. It uses port 5711 and 5596 to communicate internally with Neuroworks, and ports 5595, 5597, 5598, and 5599 to communicate externally with Moberg CNS monitors."
    },
    @{
        Port = 5598; Protocol = "TCP"; Service = "NWGateway"; Description = "Implemented in NW10GMA4, the NWGateway is a service that is deployed on acquisition station. NWGateway can host multiple proxies to communicate with different clients. CNS proxy implemented in NW10GMA4 enables data flow between non-NW client like the Mobery CNS and NW components. It uses port 5711 and 5596 to communicate internally with Neuroworks, and ports 5595, 5597, 5598, and 5599 to communicate externally with Moberg CNS monitors."
    },
    @{
        Port = 5599; Protocol = "TCP"; Service = "NWGateway"; Description = "Implemented in NW10GMA4, the NWGateway is a service that is deployed on acquisition station. NWGateway can host multiple proxies to communicate with different clients. CNS proxy implemented in NW10GMA4 enables data flow between non-NW client like the Mobery CNS and NW components. It uses port 5711 and 5596 to communicate internally with Neuroworks, and ports 5595, 5597, 5598, and 5599 to communicate externally with Moberg CNS monitors."
    },
    @{
        Port = 5711; Protocol = "TCP"; Service = "NWGateway"; Description = "Implemented in NW10GMA4, the NWGateway is a service that is deployed on acquisition station. NWGateway can host multiple proxies to communicate with different clients. CNS proxy implemented in NW10GMA4 enables data flow between non-NW client like the Mobery CNS and NW components. It uses port 5711 and 5596 to communicate internally with Neuroworks, and ports 5595, 5597, 5598, and 5599 to communicate externally with Moberg CNS monitors."
    },
    @{
        Port = 2100; Protocol = "TCP"; Service = "NWSentry"; Description = "The NWSentry service opens TCP connections to monitor the health of Natus applications or services and acts as a software watchdog."
    },
    @{
        Port = 2101; Protocol = "TCP"; Service = "NWSentry"; Description = "The NWSentry service opens TCP connections to monitor the health of Natus applications or services and acts as a software watchdog."
    },
    @{
        Port = 2001; Protocol = "TCP"; Service = "NWSignal"; Description = "The NWSignal service opens TCP connections to communicate with other applications and services. It receives raw data from Natus EEG/PSG hardware physically connected to patients and sends data to other EEG/PSG applications and services."
    },
    @{
        Port = 2002; Protocol = "TCP"; Service = "NWSignal"; Description = "The NWSignal service opens TCP connections to communicate with other applications and services. It receives raw data from Natus EEG/PSG hardware physically connected to patients and sends data to other EEG/PSG applications and services."
    },
    @{
        Port = 2003; Protocol = "TCP"; Service = "NWSignal"; Description = "The NWSignal service opens TCP connections to communicate with other applications and services. It receives raw data from Natus EEG/PSG hardware physically connected to patients and sends data to other EEG/PSG applications and services."
    },
    @{
        Port = 2004; Protocol = "TCP"; Service = "NWSignal"; Description = "The NWSignal service opens TCP connections to communicate with other applications and services. It receives raw data from Natus EEG/PSG hardware physically connected to patients and sends data to other EEG/PSG applications and services."
    },
    @{
        Port = 2005; Protocol = "TCP"; Service = "NWSignal"; Description = "The NWSignal service opens TCP connections to communicate with other applications and services. It receives raw data from Natus EEG/PSG hardware physically connected to patients and sends data to other EEG/PSG applications and services."
    },
    @{
        Port = 2006; Protocol = "TCP"; Service = "NWSignal"; Description = "The NWSignal service opens TCP connections to communicate with other applications and services. It receives raw data from Natus EEG/PSG hardware physically connected to patients and sends data to other EEG/PSG applications and services."
    },
    @{
        Port = 2007; Protocol = "TCP"; Service = "NWSignal"; Description = "The NWSignal service opens TCP connections to communicate with other applications and services. It receives raw data from Natus EEG/PSG hardware physically connected to patients and sends data to other EEG/PSG applications and services."
    },
    @{
        Port = 2008; Protocol = "TCP"; Service = "NWSignal"; Description = "The NWSignal service opens TCP connections to communicate with other applications and services. It receives raw data from Natus EEG/PSG hardware physically connected to patients and sends data to other EEG/PSG applications and services."
    },
    @{
        Port = 2009; Protocol = "TCP"; Service = "NWSignal"; Description = "The NWSignal service opens TCP connections to communicate with other applications and services. It receives raw data from Natus EEG/PSG hardware physically connected to patients and sends data to other EEG/PSG applications and services."
    },
    @{
        Port = 2010; Protocol = "TCP"; Service = "NWSignal"; Description = "The NWSignal service opens TCP connections to communicate with other applications and services. It receives raw data from Natus EEG/PSG hardware physically connected to patients and sends data to other EEG/PSG applications and services."
    },
    @{
        Port = 2011; Protocol = "TCP"; Service = "NWSignal"; Description = "The NWSignal service opens TCP connections to communicate with other applications and services. It receives raw data from Natus EEG/PSG hardware physically connected to patients and sends data to other EEG/PSG applications and services."
    },
    @{
        Port = 2012; Protocol = "TCP"; Service = "NWSignal"; Description = "The NWSignal service opens TCP connections to communicate with other applications and services. It receives raw data from Natus EEG/PSG hardware physically connected to patients and sends data to other EEG/PSG applications and services."
    },
    @{
        Port = 2013; Protocol = "TCP"; Service = "NWSignal"; Description = "The NWSignal service opens TCP connections to communicate with other applications and services. It receives raw data from Natus EEG/PSG hardware physically connected to patients and sends data to other EEG/PSG applications and services."
    },
    @{
        Port = 2014; Protocol = "TCP"; Service = "NWSignal"; Description = "The NWSignal service opens TCP connections to communicate with other applications and services. It receives raw data from Natus EEG/PSG hardware physically connected to patients and sends data to other EEG/PSG applications and services."
    },
    @{
        Port = 2015; Protocol = "TCP"; Service = "NWSignal"; Description = "The NWSignal service opens TCP connections to communicate with other applications and services. It receives raw data from Natus EEG/PSG hardware physically connected to patients and sends data to other EEG/PSG applications and services."
    },
    @{
        Port = 2016; Protocol = "TCP"; Service = "NWSignal"; Description = "The NWSignal service opens TCP connections to communicate with other applications and services. It receives raw data from Natus EEG/PSG hardware physically connected to patients and sends data to other EEG/PSG applications and services."
    },
    @{
        Port = 2017; Protocol = "TCP"; Service = "NWSignal"; Description = "The NWSignal service opens TCP connections to communicate with other applications and services. It receives raw data from Natus EEG/PSG hardware physically connected to patients and sends data to other EEG/PSG applications and services."
    },
    @{
        Port = 2018; Protocol = "TCP"; Service = "NWSignal"; Description = "The NWSignal service opens TCP connections to communicate with other applications and services. It receives raw data from Natus EEG/PSG hardware physically connected to patients and sends data to other EEG/PSG applications and services."
    },
    @{
        Port = 2019; Protocol = "TCP"; Service = "NWSignal"; Description = "The NWSignal service opens TCP connections to communicate with other applications and services. It receives raw data from Natus EEG/PSG hardware physically connected to patients and sends data to other EEG/PSG applications and services."
    },
    @{
        Port = 2022; Protocol = "TCP"; Service = "NWSignal"; Description = "The NWSignal service opens TCP connections to communicate with other applications and services. It receives raw data from Natus EEG/PSG hardware physically connected to patients and sends data to other EEG/PSG applications and services."
    },
    @{
        Port = 2023; Protocol = "TCP"; Service = "NWSignal"; Description = "The NWSignal service opens TCP connections to communicate with other applications and services. It receives raw data from Natus EEG/PSG hardware physically connected to patients and sends data to other EEG/PSG applications and services."
    },
    @{
        Port = 2024; Protocol = "TCP"; Service = "NWSignal"; Description = "The NWSignal service opens TCP connections to communicate with other applications and services. It receives raw data from Natus EEG/PSG hardware physically connected to patients and sends data to other EEG/PSG applications and services."
    },
    @{
        Port = 2040; Protocol = "TCP"; Service = "NWSignal"; Description = "The NWSignal service opens TCP connections to communicate with other applications and services. It receives raw data from Natus EEG/PSG hardware physically connected to patients and sends data to other EEG/PSG applications and services."
    },
    @{
        Port = 2041; Protocol = "TCP"; Service = "NWSignal"; Description = "The NWSignal service opens TCP connections to communicate with other applications and services. It receives raw data from Natus EEG/PSG hardware physically connected to patients and sends data to other EEG/PSG applications and services."
    },
    @{
        Port = 5093; Protocol = "TCP"; Service = "Sentinel RMS License Manager"; Description = "Integrated in NW10GMA2, this service listens on this port fo autoSCORE license and usage information from the Natus Licensing Service API."
    },
    @{
        Port = 1433; Protocol = "TCP"; Service = "SQL Server Express or Standard (MSSQLSERVER)"; Description = "The local or remote MS SQL instance that communicates with XLDB."
    },
    @{
        Port = 5610; Protocol = "TCP"; Service = "XLAlarmsSvc"; Description = "The XLAlarmsSvc service monitors notifications from other stations and an SQL."
    },
    @{
        Port = 5612; Protocol = "TCP"; Service = "XLEvtMsgSvc"; Description = "The XLEvtMsgSvc opens TCP connections to communicate with other applications and services."
    },
    @{
        Port = 5620; Protocol = "TCP"; Service = "XLEvtMsgSvc"; Description = "The XLEvtMsgSvc opens TCP connections to communicate with other applications and services."
    },
    @{
        Port = 5621; Protocol = "TCP"; Service = "XLEvtMsgSvc"; Description = "The XLEvtMsgSvc opens TCP connections to communicate with other applications and services."
    },
    @{
        Port = 50000; Protocol = "TCP"; Service = "XLMediaServer"; Description = "The XLMediaServer service manages video data acquisition, broadcasting and storage."
    },
    @{
        Port = 50000; Protocol = "UDP"; Service = "XLMediaServer"; Description = "The XLMediaServer service manages video data acquisition, broadcasting and storage."
    },
    @{
        Port = 50001; Protocol = "TCP"; Service = "XLMediaServer"; Description = "The XLMediaServer service manages video data acquisition, broadcasting and storage."
    },
    @{
        Port = 50001; Protocol = "UDP"; Service = "XLMediaServer"; Description = "The XLMediaServer service manages video data acquisition, broadcasting and storage."
    },
    @{
        Port = 50002; Protocol = "TCP"; Service = "XLMediaServer"; Description = "The XLMediaServer service manages video data acquisition, broadcasting and storage."
    },
    @{
        Port = 50002; Protocol = "UDP"; Service = "XLMediaServer"; Description = "The XLMediaServer service manages video data acquisition, broadcasting and storage."
    },
    @{
        Port = 50003; Protocol = "TCP"; Service = "XLMediaServer"; Description = "The XLMediaServer service manages video data acquisition, broadcasting and storage."
    },
    @{
        Port = 50003; Protocol = "UDP"; Service = "XLMediaServer"; Description = "The XLMediaServer service manages video data acquisition, broadcasting and storage."
    },
    @{
        Port = 52000; Protocol = "TCP"; Service = "XLMediaServer"; Description = "The XLMediaServer service manages video data acquisition, broadcasting and storage."
    },
    @{
        Port = 52000; Protocol = "UDP"; Service = "XLMediaServer"; Description = "The XLMediaServer service manages video data acquisition, broadcasting and storage."
    },
    @{
        Port = 52001; Protocol = "TCP"; Service = "XLMediaServer"; Description = "The XLMediaServer service manages video data acquisition, broadcasting and storage."
    },
    @{
        Port = 52001; Protocol = "UDP"; Service = "XLMediaServer"; Description = "The XLMediaServer service manages video data acquisition, broadcasting and storage."
    },
    @{
        Port = 5301; Protocol = "TCP"; Service = "XLMediaServer"; Description = "The XLMediaServer service manages video data acquisition, broadcasting and storage."
    },
    @{
        Port = 5301; Protocol = "UDP"; Service = "XLMediaServer"; Description = "The XLMediaServer service manages video data acquisition, broadcasting and storage."
    },
    @{
        Port = 5302; Protocol = "TCP"; Service = "XLMediaServer"; Description = "The XLMediaServer service manages video data acquisition, broadcasting and storage."
    },
    @{
        Port = 5302; Protocol = "UDP"; Service = "XLMediaServer"; Description = "The XLMediaServer service manages video data acquisition, broadcasting and storage."
    },
    @{
        Port = 5303; Protocol = "TCP"; Service = "XLMediaServer"; Description = "The XLMediaServer service manages video data acquisition, broadcasting and storage."
    },
    @{
        Port = 5303; Protocol = "UDP"; Service = "XLMediaServer"; Description = "The XLMediaServer service manages video data acquisition, broadcasting and storage."
    },
    @{
        Port = 5304; Protocol = "TCP"; Service = "XLMediaServer"; Description = "The XLMediaServer service manages video data acquisition, broadcasting and storage."
    },
    @{
        Port = 5304; Protocol = "UDP"; Service = "XLMediaServer"; Description = "The XLMediaServer service manages video data acquisition, broadcasting and storage."
    },
    @{
        Port = 5305; Protocol = "TCP"; Service = "XLMediaServer"; Description = "The XLMediaServer service manages video data acquisition, broadcasting and storage."
    },
    @{
        Port = 5305; Protocol = "UDP"; Service = "XLMediaServer"; Description = "The XLMediaServer service manages video data acquisition, broadcasting and storage."
    },
    @{
        Port = 5306; Protocol = "TCP"; Service = "XLMediaServer"; Description = "The XLMediaServer service manages video data acquisition, broadcasting and storage."
    },
    @{
        Port = 5306; Protocol = "UDP"; Service = "XLMediaServer"; Description = "The XLMediaServer service manages video data acquisition, broadcasting and storage."
    },
    @{
        Port = 5307; Protocol = "TCP"; Service = "XLMediaServer"; Description = "The XLMediaServer service manages video data acquisition, broadcasting and storage."
    },
    @{
        Port = 5307; Protocol = "UDP"; Service = "XLMediaServer"; Description = "The XLMediaServer service manages video data acquisition, broadcasting and storage."
    },
    @{
        Port = 5308; Protocol = "TCP"; Service = "XLMediaServer"; Description = "The XLMediaServer service manages video data acquisition, broadcasting and storage."
    },
    @{
        Port = 5308; Protocol = "UDP"; Service = "XLMediaServer"; Description = "The XLMediaServer service manages video data acquisition, broadcasting and storage."
    },
    @{
        Port = 5500; Protocol = "TCP"; Service = "XLMediaServer"; Description = "The XLMediaServer service manages video data acquisition, broadcasting and storage."
    },
    @{
        Port = 5500; Protocol = "UDP"; Service = "XLMediaServer"; Description = "The XLMediaServer service manages video data acquisition, broadcasting and storage."
    },
    @{
        Port = 5501; Protocol = "TCP"; Service = "XLMediaServer"; Description = "The XLMediaServer service manages video data acquisition, broadcasting and storage."
    },
    @{
        Port = 5501; Protocol = "UDP"; Service = "XLMediaServer"; Description = "The XLMediaServer service manages video data acquisition, broadcasting and storage."
    },
    @{
        Port = 5502; Protocol = "TCP"; Service = "XLMediaServer"; Description = "The XLMediaServer service manages video data acquisition, broadcasting and storage."
    },
    @{
        Port = 5502; Protocol = "UDP"; Service = "XLMediaServer"; Description = "The XLMediaServer service manages video data acquisition, broadcasting and storage."
    },
    @{
        Port = 554; Protocol = "TCP"; Service = "XLMediaServer"; Description = "The XLMediaServer service manages video data acquisition, broadcasting and storage."
    },
    @{
        Port = 554; Protocol = "UDP"; Service = "XLMediaServer"; Description = "The XLMediaServer service manages video data acquisition, broadcasting and storage."
    },
    @{
        Port = 60000; Protocol = "TCP"; Service = "XLMediaServer"; Description = "The XLMediaServer service manages video data acquisition, broadcasting and storage."
    },
    @{
        Port = 60000; Protocol = "UDP"; Service = "XLMediaServer"; Description = "The XLMediaServer service manages video data acquisition, broadcasting and storage."
    },
    @{
        Port = 60001; Protocol = "TCP"; Service = "XLMediaServer"; Description = "The XLMediaServer service manages video data acquisition, broadcasting and storage."
    },
    @{
        Port = 60001; Protocol = "UDP"; Service = "XLMediaServer"; Description = "The XLMediaServer service manages video data acquisition, broadcasting and storage."
    },
    @{
        Port = 60002; Protocol = "TCP"; Service = "XLMediaServer"; Description = "The XLMediaServer service manages video data acquisition, broadcasting and storage."
    },
    @{
        Port = 60002; Protocol = "UDP"; Service = "XLMediaServer"; Description = "The XLMediaServer service manages video data acquisition, broadcasting and storage."
    },
    @{
        Port = 60003; Protocol = "TCP"; Service = "XLMediaServer"; Description = "The XLMediaServer service manages video data acquisition, broadcasting and storage."
    },
    @{
        Port = 60003; Protocol = "UDP"; Service = "XLMediaServer"; Description = "The XLMediaServer service manages video data acquisition, broadcasting and storage."
    },
    @{
        Port = 62000; Protocol = "TCP"; Service = "XLMediaServer"; Description = "The XLMediaServer service manages video data acquisition, broadcasting and storage."
    },
    @{
        Port = 62000; Protocol = "UDP"; Service = "XLMediaServer"; Description = "The XLMediaServer service manages video data acquisition, broadcasting and storage."
    },
    @{
        Port = 62001; Protocol = "TCP"; Service = "XLMediaServer"; Description = "The XLMediaServer service manages video data acquisition, broadcasting and storage."
    },
    @{
        Port = 62001; Protocol = "UDP"; Service = "XLMediaServer"; Description = "The XLMediaServer service manages video data acquisition, broadcasting and storage."
    },
    @{
        Port = 80; Protocol = "TCP"; Service = "XLMediaServer"; Description = "The XLMediaServer service manages video data acquisition, broadcasting and storage."
    },
    @{
        Port = 80; Protocol = "UDP"; Service = "XLMediaServer"; Description = "The XLMediaServer service manages video data acquisition, broadcasting and storage."
    },
    @{
        Port = 5611; Protocol = "TCP"; Service = "XLNetCOMBridge"; Description = "The XLNetCOMBridge service TCP connections to communicate with other applications and services"
    }
)

# Function to write colored output on the same line
function Write-Result($message, $status, $responseTime) {
    $statusText = switch ($status) {
        "Open" { "OPEN" }
        "Closed" { "CLOSED" }
        "No Response" { "NO RESPONSE" }
        default { "UNKNOWN" }
    }
    
    $fullMessage = "$message - Response Time: $responseTime ms - Status: $statusText"
    
    switch ($status) {
        "Open" { Write-Host $fullMessage -ForegroundColor Green }
        "Closed" { Write-Host $fullMessage -ForegroundColor Red }
        "No Response" { Write-Host $fullMessage -ForegroundColor Red }
        default { Write-Host $fullMessage -ForegroundColor Yellow }
    }
}

# Store results
$results = @()

# Test each port
foreach ($entry in $ports) {
    $port = [int]$entry.Port
    $protocol = $entry.Protocol.ToUpper()
    $service = $entry.Service
    $description = $entry.Description

    # Start timing
    $stopwatch = [System.Diagnostics.Stopwatch]::StartNew()
    
    $status = switch ($protocol) {
        "TCP" {
            try {
                $tcpClient = New-Object System.Net.Sockets.TcpClient
                $tcpClient.ReceiveTimeout = 3000
                $tcpClient.SendTimeout = 3000
                $tcpClient.Connect($TargetIP, $port)
                if ($tcpClient.Connected) {
                    $tcpClient.Close()
                    "Open"
                } else {
                    "Closed"
                }
            } catch {
                "Closed"
            }
        }
        "UDP" {
            try {
                $udpClient = New-Object System.Net.Sockets.UdpClient
                $udpClient.Client.SendTimeout = 3000
                $udpClient.Client.ReceiveTimeout = 3000
                $udpClient.Connect($TargetIP, $port)
                $udpClient.Send([byte[]](65), 1) | Out-Null
                $remoteEP = New-Object System.Net.IPEndPoint([System.Net.IPAddress]::Any, 0)
                $udpClient.Receive([ref]$remoteEP) | Out-Null
                $udpClient.Close()
                "Open"
            } catch {
                "No Response"
            }
        }
        default { "Unknown Protocol" }
    }

    # Stop timing
    $stopwatch.Stop()
    $responseTime = $stopwatch.ElapsedMilliseconds

    Write-Result "Port $port ($protocol) - $service" $status $responseTime

    $results += [PSCustomObject]@{
        Port = $port
        Protocol = $protocol
        Service = $service
        Description = $description
        Status = $status
        ResponseTime = $responseTime
    }
}

# Ask if user wants to export to HTML
$export = Read-Host "Do you want to export the results to HTML? (Y/N)"
if ($export -eq "Y" -or $export -eq "y") {
    # Get current date in yyyymmdd format
    $currentDate = Get-Date -Format "yyyyMMdd"
    
    # Get desktop path
    $desktopPath = [Environment]::GetFolderPath("Desktop")
    
    # Create filename with hostname/IP and date
    $filename = "PortTest_$($TargetIP)_$currentDate.html"
    $fullPath = Join-Path $desktopPath $filename
    
    # Create HTML with custom styling
    $htmlHeader = @"
<!DOCTYPE html>
<html>
<head>
    <title>Port Test Results - $TargetIP</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        h1 { color: #333; }
        table { border-collapse: collapse; width: 100%; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .open { color: green; font-weight: bold; }
        .closed { color: red; font-weight: bold; }
        .no-response { color: red; font-weight: bold; }
        .info { margin-bottom: 20px; }
    </style>
</head>
<body>
    <h1>Port Test Results</h1>
    <div class="info">
        <p><strong>Target:</strong> $TargetIP</p>
        <p><strong>Test Date:</strong> $(Get-Date -Format "yyyy-MM-dd HH:mm:ss")</p>
    </div>
"@

    $htmlTable = $results | ConvertTo-Html -Property Port, Protocol, Service, Description, Status, ResponseTime -Fragment
    
    # Add CSS classes to status cells
    $htmlTable = $htmlTable -replace '<td>Open</td>', '<td class="open">Open</td>'
    $htmlTable = $htmlTable -replace '<td>Closed</td>', '<td class="closed">Closed</td>'
    $htmlTable = $htmlTable -replace '<td>No Response</td>', '<td class="no-response">No Response</td>'
    
    $htmlFooter = @"
</body>
</html>
"@

    $fullHtml = $htmlHeader + $htmlTable + $htmlFooter
    $fullHtml | Out-File -Encoding UTF8 $fullPath
    
    Write-Host "Results exported to: $fullPath" -ForegroundColor Cyan
    Write-Host "Opening file..." -ForegroundColor Cyan
    Start-Process $fullPath
}