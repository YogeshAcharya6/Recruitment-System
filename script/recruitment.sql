
CREATE TABLE IF NOT EXISTS `dimcandidatestatus` (
  `idCandidateStatus` int(11) NOT NULL,
  `candidateStatusDesc` varchar(64) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `dimcandidatestatus`
--

INSERT INTO `dimcandidatestatus` (`idCandidateStatus`, `candidateStatusDesc`) VALUES
(1, 'Available'),
(2, 'Not Available'),
(3, 'Banned'),
(4, 'In Progress');

-- --------------------------------------------------------

--
-- Table structure for table `dimcandiposstatus`
--

CREATE TABLE IF NOT EXISTS `dimcandiposstatus` (
  `idCandiPosStatus` int(11) NOT NULL,
  `candiPosStatusDesc` varchar(64) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `dimcandiposstatus`
--

INSERT INTO `dimcandiposstatus` (`idCandiPosStatus`, `candiPosStatusDesc`) VALUES
(1, 'Assigned'),
(2, 'Rejected'),
(3, 'On Hold'),
(4, 'Shortlisted'),
(5, 'Not Interested');

-- --------------------------------------------------------

--
-- Table structure for table `dimcertification`
--

CREATE TABLE IF NOT EXISTS `dimcertification` (
  `idCertification` int(11) NOT NULL,
  `Certification` varchar(64) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `dimcertification`
--

INSERT INTO `dimcertification` (`idCertification`, `Certification`) VALUES
(1, 'RHCSA'),
(2, 'CCNA'),
(3, 'MCES'),
(4, 'JCHNP'),
(5, 'RCHE'),
(6, 'Java'),
(7, 'C'),
(8, 'C++'),
(9, 'Web Development');

-- --------------------------------------------------------

--
-- Table structure for table `dimdept`
--

CREATE TABLE IF NOT EXISTS `dimdept` (
  `idDept` int(11) NOT NULL,
  `DeptName` varchar(64) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `dimdept`
--

INSERT INTO `dimdept` (`idDept`, `DeptName`) VALUES
(1, 'IT'),
(2, 'Mechanical'),
(3, 'Electronics'),
(4, 'HR'),
(5, 'Account'),
(6, 'Networking'),
(7, 'Computer');

-- --------------------------------------------------------

--
-- Table structure for table `dimdesignation`
--

CREATE TABLE IF NOT EXISTS `dimdesignation` (
  `idDesignation` int(11) NOT NULL,
  `Designation` varchar(64) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `dimdesignation`
--

INSERT INTO `dimdesignation` (`idDesignation`, `Designation`) VALUES
(1, 'HR'),
(2, 'HR Representive'),
(3, 'HR Manager');

-- --------------------------------------------------------

--
-- Table structure for table `dimelement`
--

CREATE TABLE IF NOT EXISTS `dimelement` (
  `idElement` int(11) NOT NULL,
  `elementDescription` varchar(64) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `dimelement`
--

INSERT INTO `dimelement` (`idElement`, `elementDescription`) VALUES
(1, 'AddJobDescriptionBtn');

-- --------------------------------------------------------

--
-- Table structure for table `dimelementtype`
--

CREATE TABLE IF NOT EXISTS `dimelementtype` (
  `idElementType` int(11) NOT NULL,
  `elementType` varchar(24) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `dimelementtype`
--

INSERT INTO `dimelementtype` (`idElementType`, `elementType`) VALUES
(1, 'Button'),
(2, 'Menu List Header'),
(3, 'Menu List Option');

-- --------------------------------------------------------

--
-- Table structure for table `diminteractionstatus`
--

CREATE TABLE IF NOT EXISTS `diminteractionstatus` (
  `idinteractionStatus` int(11) NOT NULL,
  `interactionStatusDesc` varchar(64) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `diminteractionstatus`
--

INSERT INTO `diminteractionstatus` (`idinteractionStatus`, `interactionStatusDesc`) VALUES
(1, 'Short Listed'),
(2, 'On Hold'),
(3, 'Rejected'),
(4, 'Selected');

-- --------------------------------------------------------

--
-- Table structure for table `diminterviewtype`
--

CREATE TABLE IF NOT EXISTS `diminterviewtype` (
  `idInterviewType` int(11) NOT NULL,
  `interviewTypeDesc` varchar(64) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `diminterviewtype`
--

INSERT INTO `diminterviewtype` (`idInterviewType`, `interviewTypeDesc`) VALUES
(1, 'Face To Face'),
(2, 'Panel Interview'),
(3, 'Group Interview'),
(4, 'Phone Interview'),
(5, 'Apptitude Test'),
(6, 'Programming Test'),
(7, 'Technical Interview'),
(8, 'HR Interview');

-- --------------------------------------------------------

--
-- Table structure for table `dimnontechskill`
--

CREATE TABLE IF NOT EXISTS `dimnontechskill` (
  `idNonTechSkill` int(11) NOT NULL,
  `NonTechSkill` varchar(64) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `dimnontechskill`
--

INSERT INTO `dimnontechskill` (`idNonTechSkill`, `NonTechSkill`) VALUES
(1, 'Communication'),
(2, 'LeaderShip'),
(3, 'DecisionMaking'),
(4, 'IntellectualCuriosity'),
(5, 'Efficiency'),
(6, 'Project Management'),
(7, 'Operations Management'),
(8, 'Client Relationship Management'),
(9, 'Facilities Management'),
(10, 'Team Management'),
(11, 'Delivery Management');

-- --------------------------------------------------------

--
-- Table structure for table `dimopstatus`
--

CREATE TABLE IF NOT EXISTS `dimopstatus` (
  `idOpStatus` int(11) NOT NULL,
  `opStatusDesc` varchar(64) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `dimopstatus`
--

INSERT INTO `dimopstatus` (`idOpStatus`, `opStatusDesc`) VALUES
(1, 'New'),
(2, 'Filled'),
(3, 'Closed');

-- --------------------------------------------------------

--
-- Table structure for table `dimposition`
--

CREATE TABLE IF NOT EXISTS `dimposition` (
  `idPosition` int(11) NOT NULL,
  `Position` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Table structure for table `dimqualification`
--

CREATE TABLE IF NOT EXISTS `dimqualification` (
  `idQuali` int(11) NOT NULL,
  `QualiName` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `dimqualification`
--

INSERT INTO `dimqualification` (`idQuali`, `QualiName`) VALUES
(1, 'BE'),
(2, 'ME'),
(3, 'BCA'),
(4, 'MCA'),
(5, 'BCS'),
(6, 'MCS'),
(7, 'BTech'),
(8, 'MTech'),
(9, 'BE(Comp/IT)');

-- --------------------------------------------------------

--
-- Table structure for table `dimrole`
--

CREATE TABLE IF NOT EXISTS `dimrole` (
  `idRole` int(11) NOT NULL,
  `roleDescription` varchar(64) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `dimrole`
--

INSERT INTO `dimrole` (`idRole`, `roleDescription`) VALUES
(1, 'HR'),
(2, 'HR Representive'),
(3, 'Employee'),
(4, 'Interviewer'),
(5, 'Evaluator');

-- --------------------------------------------------------

--
-- Table structure for table `dimskill`
--

CREATE TABLE IF NOT EXISTS `dimskill` (
  `idSkill` int(11) NOT NULL,
  `SkillName` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `dimskill`
--

INSERT INTO `dimskill` (`idSkill`, `SkillName`) VALUES
(1, 'C'),
(2, 'Java'),
(3, 'VB.Net'),
(4, 'Oracle'),
(5, 'ASP.Net'),
(6, 'Linux/Unix'),
(7, 'Troubleshooting'),
(8, 'Networking'),
(9, 'PHP'),
(10, 'SQL');

-- --------------------------------------------------------

--
-- Table structure for table `dimstatus`
--

CREATE TABLE IF NOT EXISTS `dimstatus` (
  `idStatus` int(11) NOT NULL,
  `Status` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `dimstatus`
--

INSERT INTO `dimstatus` (`idStatus`, `Status`) VALUES
(1, 'New'),
(2, 'Work In Progress'),
(3, 'Suspended'),
(4, 'Closed'),
(5, 'Force Closed');

-- --------------------------------------------------------

--
-- Table structure for table `tblActivefeedback`
--

CREATE TABLE IF NOT EXISTS `tblActivefeedback` (
  `idActivefeedback` int(11) NOT NULL,
  `CandidateRRId` int(11) NOT NULL,
  `IsActive` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tblcadiskill`
--

CREATE TABLE IF NOT EXISTS `tblcadiskill` (
  `idCandiSkill` int(11) NOT NULL,
  `CandidateId` int(11) NOT NULL,
  `SkillId` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tblcandidate`
--

CREATE TABLE IF NOT EXISTS `tblcandidate` (
  `idCandidate` int(11) NOT NULL,
  `CandidateName` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `Resume` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `CurrentCompany` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `CurrentDesignation` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `CurrentSalary` int(11) DEFAULT NULL,
  `NoticePeriod` int(11) DEFAULT NULL,
  `Role` text COLLATE utf8_unicode_ci NOT NULL,
  `Responsibility` text COLLATE utf8_unicode_ci NOT NULL,
  `TotalExp` int(11) DEFAULT NULL,
  `RelevantExp` int(11) DEFAULT NULL,
  `MobNo` int(22) DEFAULT NULL,
  `AltContactNo` int(22) DEFAULT NULL,
  `EmailId` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Address` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `RegDate` datetime DEFAULT NULL,
  `CandidateStatusId` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



CREATE TABLE IF NOT EXISTS `tblcandiquali` (
  `idCandiQuali` int(11) NOT NULL,
  `CandidateId` int(11) NOT NULL,
  `QualiId` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tblcertification`
--

CREATE TABLE IF NOT EXISTS `tblcertification` (
  `idCertificationtbl` int(11) NOT NULL,
  `CandidateId` int(11) NOT NULL,
  `CertificationId` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tblconfigure`
--

CREATE TABLE IF NOT EXISTS `tblconfigure` (
  `idConfigure` int(11) NOT NULL,
  `searchKey` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `searchValue` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `IsActive` int(11) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `tblconfigure`
--

INSERT INTO `tblconfigure` (`idConfigure`, `searchKey`, `searchValue`, `IsActive`) VALUES
(1, 'AllowMulSimultaneousLogins', 'Y', 1),
(2, 'SessionTimeOut', '20', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tblelements`
--

CREATE TABLE IF NOT EXISTS `tblelements` (
  `idElement` int(11) NOT NULL,
  `elementTypeId` int(11) NOT NULL,
  `elementDesc` varchar(32) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `tblelements`
--

INSERT INTO `tblelements` (`idElement`, `elementTypeId`, `elementDesc`) VALUES
(1, 2, 'Admin'),
(2, 3, 'AddUser'),
(3, 3, 'User'),
(4, 2, 'Maintain'),
(5, 3, 'ChangePassword'),
(6, 3, 'Roles'),
(7, 3, 'Configure'),
(8, 3, 'Elements'),
(9, 2, 'JobDescription'),
(10, 3, 'RecruitmentRequest'),
(11, 3, 'Candidates'),
(12, 3, 'Dashboard'),
(13, 3, 'RRSchedule'),
(14, 3, 'InterviewerInterface'),
(15, 2, 'Alerts'),
(16, 1, 'AddJobDescriptionBtn'),
(17, 1, 'UpdateJobDesacriptionBtn'),
(18, 1, 'AddRecruitmentRequestBtn'),
(19, 1, 'UpdateRecruitmentRequestBtn'),
(20, 1, 'AddCandidateBtn'),
(21, 1, 'UpdateCndidateBtn');

-- --------------------------------------------------------

--
-- Table structure for table `tblinteraction`
--

CREATE TABLE IF NOT EXISTS `tblinteraction` (
  `idRRSchedule` int(11) NOT NULL,
  `CandidateRRId` int(11) DEFAULT NULL,
  `InterviewTypeId` int(11) DEFAULT NULL,
  `intDate` datetime DEFAULT NULL,
  `altIntDate` datetime DEFAULT NULL,
  `interactionStatusId` int(11) DEFAULT NULL,
  `IsActiveInteraction` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `tblinteraction`
--


CREATE TABLE IF NOT EXISTS `tblinteractionfeedback` (
  `idRRCandidateFeedback` int(11) NOT NULL,
  `feedback` text COLLATE utf8_unicode_ci NOT NULL,
  `ScheduleRRId` int(11) DEFAULT NULL,
  `RegId` int(11) DEFAULT NULL,
  `LUDT` datetime DEFAULT NULL,
  `IsActive` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



CREATE TABLE IF NOT EXISTS `tblInterviewer` (
  `idInterview` int(11) NOT NULL,
  `RRScheduleId` int(11) NOT NULL,
  `RegId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



CREATE TABLE IF NOT EXISTS `tbljdquali` (
  `idJDQuali` int(11) NOT NULL,
  `JobDescId` int(11) NOT NULL,
  `QualiId` int(11) NOT NULL,
  `IsActive` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


CREATE TABLE IF NOT EXISTS `tbljdskill` (
  `idJDSkill` int(11) NOT NULL,
  `JobDescId` int(11) NOT NULL,
  `SkillId` int(11) DEFAULT NULL,
  `IsActive` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `tbljobdesc` (
  `idJobDesc` int(11) NOT NULL,
  `PositionId` int(11) NOT NULL,
  `ExperianceMin` int(11) DEFAULT NULL,
  `ExperianceMAx` int(11) DEFAULT NULL,
  `RegDate` datetime DEFAULT NULL,
  `Role` text COLLATE utf8_unicode_ci NOT NULL,
  `Responsibility` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


CREATE TABLE IF NOT EXISTS `tblloginhist` (
  `idLoginHist` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `idString` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `LoginTime` datetime DEFAULT NULL,
  `LogoutTime` datetime DEFAULT NULL,
  `IsTimeOut` int(11) DEFAULT NULL,
  `lastActionDateTime` datetime DEFAULT NULL,
  `clientIP` varchar(64) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


CREATE TABLE IF NOT EXISTS `tblnontechskill` (
  `idNonTechSkilltbl` int(11) NOT NULL,
  `CandidateId` int(11) NOT NULL,
  `dimNonTechSkillId` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


CREATE TABLE IF NOT EXISTS `tblopening` (
  `idOpening` int(11) NOT NULL,
  `RRId` int(11) NOT NULL,
  `PositionUId` int(11) DEFAULT NULL,
  `OpStatusId` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


CREATE TABLE IF NOT EXISTS `tblposition` (
  `idPosition` int(11) NOT NULL,
  `Position` varchar(32) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


CREATE TABLE IF NOT EXISTS `tblregistration` (
  `idReg` int(11) NOT NULL,
  `Name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `EmpId` int(11) NOT NULL,
  `DeptId` int(11) DEFAULT NULL,
  `DesignationId` int(11) DEFAULT NULL,
  `MobNo` int(22) DEFAULT NULL,
  `AltContactNo` int(22) DEFAULT NULL,
  `EmailId` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Address` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


INSERT INTO `tblregistration` (`idReg`, `Name`, `EmpId`, `DeptId`, `DesignationId`, `MobNo`, `AltContactNo`, `EmailId`, `Address`) VALUES
(1, 'Abhijit laghate', 1, 1, 1, 2147483647, 2147483647, 'abhijit@phoenixsystech.in', 'Pune'),
(2, 'Suhas', 2, 1, 1, 1111111111, 1111111111, 'suhas@gemail.com', 'Pune'),
(3, 'Gitika', 3, 2, 2, 2147483647, 2147483647, 'gitika@gmail.com', 'Pune'),
(4, 'hfgdsd', 87, 1, 1, 2147483647, NULL, 'gbfdfd@fds.gf', 'fjgdfd'),
(5, 'fbc', 0, 1, 1, 1324353333, NULL, 'ggrdfs@gefs.rtdf', 'dfsda');


CREATE TABLE IF NOT EXISTS `tblrolepermissions` (
  `idRollPerm` int(11) NOT NULL,
  `roleId` int(11) NOT NULL,
  `elementId` int(11) NOT NULL,
  `IsPermitted` int(11) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


INSERT INTO `tblrolepermissions` (`idRollPerm`, `roleId`, `elementId`, `IsPermitted`) VALUES
(1, 1, 1, 1),
(2, 1, 2, 1),
(3, 1, 3, 1),
(4, 1, 4, 1),
(5, 1, 5, 1),
(6, 1, 6, 1),
(7, 1, 7, 1),
(8, 1, 8, 1),
(9, 1, 9, 1),
(10, 1, 10, 1),
(11, 1, 11, 1),
(12, 1, 12, 1),
(13, 1, 13, 1),
(14, 1, 14, 1),
(15, 1, 15, 1),
(16, 2, 1, 1),
(17, 2, 2, 1),
(18, 2, 3, 1),
(19, 2, 4, 1),
(20, 2, 5, 1),
(21, 2, 6, 1),
(22, 2, 7, 1),
(23, 2, 8, 1),
(24, 2, 9, 1),
(25, 2, 10, 1),
(26, 2, 11, 1),
(27, 2, 12, 1),
(28, 2, 13, 1),
(29, 2, 14, 1),
(30, 2, 15, 1),
(31, 2, 16, 1),
(32, 1, 16, 1),
(33, 1, 17, 1),
(34, 1, 18, 1),
(35, 1, 19, 1),
(36, 1, 20, 1),
(37, 1, 21, 1);


CREATE TABLE IF NOT EXISTS `tblrr` (
  `idRR` int(11) NOT NULL,
  `EmpId` int(11) NOT NULL,
  `DeptId` int(11) DEFAULT NULL,
  `Openings` int(11) DEFAULT NULL,
  `JobDescId` int(11) DEFAULT NULL,
  `RequestDate` date DEFAULT NULL,
  `ExpectedDate` date DEFAULT NULL,
  `SalaryMin` int(11) DEFAULT NULL,
  `SalaryMax` int(11) DEFAULT NULL,
  `Status` int(11) DEFAULT NULL,
  `RegDate` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


CREATE TABLE IF NOT EXISTS `tblrrcandidate` (
  `idRRCandidate` int(11) NOT NULL,
  `RRId` int(11) DEFAULT NULL,
  `PositionId` int(11) NOT NULL,
  `CandidateId` int(11) NOT NULL,
  `IsActive` int(11) DEFAULT NULL,
  `CandiPosStatusId` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


CREATE TABLE IF NOT EXISTS `tbluser` (
  `idUser` int(11) NOT NULL,
  `userId` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `userPassword` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `IsActive` int(11) DEFAULT NULL,
  `activeChgDT` datetime DEFAULT NULL,
  `activeChgBy` int(11) DEFAULT NULL,
  `createDT` datetime DEFAULT NULL,
  `createBy` int(11) DEFAULT NULL,
  `pwdSetDT` datetime DEFAULT NULL,
  `regId` int(11) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


INSERT INTO `tbluser` (`idUser`, `userId`, `userPassword`, `IsActive`, `activeChgDT`, `activeChgBy`, `createDT`, `createBy`, `pwdSetDT`, `regId`) VALUES
(1, 'Admin', 'Admin1234', 1, NULL, NULL, '2016-04-19 09:52:35', NULL, NULL, 1),
(2, 'Suhas', 'Suhas1234', 1, NULL, NULL, '2016-06-21 09:52:16', 1, NULL, 2),
(3, 'Gitika', 'Gitika1234', 1, NULL, NULL, '2016-06-21 09:54:15', 1, NULL, 3),
(4, 'fgdfs', 'Asdasd1234', 1, NULL, NULL, '2016-07-18 10:03:40', 1, NULL, 4),
(5, 'Swapnil', 'Swapnil1234', 1, NULL, NULL, '2016-10-19 08:25:44', 1, NULL, 5);


CREATE TABLE IF NOT EXISTS `tbluserrole` (
  `idUserRole` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `roleId` int(11) NOT NULL,
  `IsActive` int(11) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


INSERT INTO `tbluserrole` (`idUserRole`, `userId`, `roleId`, `IsActive`) VALUES
(1, 1, 1, 1),
(2, 3, 2, 1),
(3, 2, 1, 1),
(4, 2, 2, 0),
(5, 1, 2, 0);


ALTER TABLE `dimcandidatestatus`
  ADD PRIMARY KEY (`idCandidateStatus`);


ALTER TABLE `dimcandiposstatus`
  ADD PRIMARY KEY (`idCandiPosStatus`);


ALTER TABLE `dimcertification`
  ADD PRIMARY KEY (`idCertification`);


ALTER TABLE `dimdept`
  ADD PRIMARY KEY (`idDept`);


ALTER TABLE `dimdesignation`
  ADD PRIMARY KEY (`idDesignation`);


ALTER TABLE `dimelement`
  ADD PRIMARY KEY (`idElement`);


ALTER TABLE `dimelementtype`
  ADD PRIMARY KEY (`idElementType`);


ALTER TABLE `diminteractionstatus`
  ADD PRIMARY KEY (`idinteractionStatus`);


ALTER TABLE `diminterviewtype`
  ADD PRIMARY KEY (`idInterviewType`);


ALTER TABLE `dimnontechskill`
  ADD PRIMARY KEY (`idNonTechSkill`);


ALTER TABLE `dimopstatus`
  ADD PRIMARY KEY (`idOpStatus`);


ALTER TABLE `dimposition`
  ADD PRIMARY KEY (`idPosition`);


ALTER TABLE `dimqualification`
  ADD PRIMARY KEY (`idQuali`);


ALTER TABLE `dimrole`
  ADD PRIMARY KEY (`idRole`);


ALTER TABLE `dimskill`
  ADD PRIMARY KEY (`idSkill`);


ALTER TABLE `dimstatus`
  ADD PRIMARY KEY (`idStatus`);


ALTER TABLE `tblActivefeedback`
  ADD PRIMARY KEY (`idActivefeedback`),
  ADD KEY `FK_tblActivefeedback` (`CandidateRRId`);


ALTER TABLE `tblcadiskill`
  ADD PRIMARY KEY (`idCandiSkill`),
  ADD KEY `fk_tblcadiskillCandidateId` (`CandidateId`),
  ADD KEY `fk_tblcadiskillSkillId` (`SkillId`);


ALTER TABLE `tblcandidate`
  ADD PRIMARY KEY (`idCandidate`),
  ADD KEY `fk_tblcandidateCandidateStatusId` (`CandidateStatusId`);


ALTER TABLE `tblcandiquali`
  ADD PRIMARY KEY (`idCandiQuali`),
  ADD KEY `fk_tblcandiqualiCandidateId` (`CandidateId`),
  ADD KEY `fk_tblcandiqualiQualiId` (`QualiId`);


ALTER TABLE `tblcertification`
  ADD PRIMARY KEY (`idCertificationtbl`),
  ADD KEY `fk_tblcertificationCandidateId` (`CandidateId`),
  ADD KEY `fk_tblcertificationCertificationId` (`CertificationId`);


ALTER TABLE `tblconfigure`
  ADD PRIMARY KEY (`idConfigure`);


ALTER TABLE `tblelements`
  ADD PRIMARY KEY (`idElement`),
  ADD KEY `fk_tblelementselementTypeId` (`elementTypeId`);


ALTER TABLE `tblinteraction`
  ADD PRIMARY KEY (`idRRSchedule`),
  ADD KEY `FK_tblrrscheduleCandidateId` (`CandidateRRId`),
  ADD KEY `fk_tblrrscheduleInterviewTypeId` (`InterviewTypeId`),
  ADD KEY `fk_tblrrscheduleinteractionStatusId` (`interactionStatusId`);


ALTER TABLE `tblinteractionfeedback`
  ADD PRIMARY KEY (`idRRCandidateFeedback`),
  ADD KEY `FK_tblInteractionFeedbackScheduleRRId` (`ScheduleRRId`),
  ADD KEY `FK_tblInteractionFeedbackRegId` (`RegId`);


ALTER TABLE `tblInterviewer`
  ADD PRIMARY KEY (`idInterview`),
  ADD KEY `FK_tblInterviewerRegId` (`RegId`),
  ADD KEY `FK_tblInterviewerRRScheduleId` (`RRScheduleId`);


ALTER TABLE `tbljdquali`
  ADD PRIMARY KEY (`idJDQuali`),
  ADD KEY `fk_tbljdqualiJobDescId` (`JobDescId`),
  ADD KEY `fk_tbljdqualiSkillId` (`QualiId`);


ALTER TABLE `tbljdskill`
  ADD PRIMARY KEY (`idJDSkill`),
  ADD KEY `fk_tbljdskillJobDescId` (`JobDescId`),
  ADD KEY `fk_tbljdskillSkillId` (`SkillId`);


ALTER TABLE `tbljobdesc`
  ADD PRIMARY KEY (`idJobDesc`),
  ADD KEY `fk_PositionId` (`PositionId`);


ALTER TABLE `tblloginhist`
  ADD PRIMARY KEY (`idLoginHist`),
  ADD KEY `fk_userId` (`userId`);


ALTER TABLE `tblnontechskill`
  ADD PRIMARY KEY (`idNonTechSkilltbl`),
  ADD KEY `fk_tblnontechskillCandidateId` (`CandidateId`),
  ADD KEY `fk_tblnontechskilldimNonTechSkillId` (`dimNonTechSkillId`);


ALTER TABLE `tblopening`
  ADD PRIMARY KEY (`idOpening`),
  ADD KEY `fk_tblopeningRRId` (`RRId`),
  ADD KEY `fk_tblopeningOpStatusId` (`OpStatusId`);


ALTER TABLE `tblposition`
  ADD PRIMARY KEY (`idPosition`);


ALTER TABLE `tblregistration`
  ADD PRIMARY KEY (`idReg`),
  ADD KEY `fk_DeptId` (`DeptId`),
  ADD KEY `fk_DesignationId` (`DesignationId`);


ALTER TABLE `tblrolepermissions`
  ADD PRIMARY KEY (`idRollPerm`),
  ADD KEY `fk_elementId` (`elementId`),
  ADD KEY `fk_roleId1` (`roleId`);


ALTER TABLE `tblrr`
  ADD PRIMARY KEY (`idRR`),
  ADD KEY `fk_JobDescId` (`JobDescId`),
  ADD KEY `fk_Status` (`Status`),
  ADD KEY `fk_tblrrDeptId` (`DeptId`);


ALTER TABLE `tblrrcandidate`
  ADD PRIMARY KEY (`idRRCandidate`),
  ADD KEY `FK_tblrrcandidateRRId` (`RRId`),
  ADD KEY `fk_tblrrcandidateCandidateId` (`CandidateId`),
  ADD KEY `fk_tblrrcandidatePositionId` (`PositionId`),
  ADD KEY `fk_tblrrcandidateCandiPosStatusId` (`CandiPosStatusId`);


ALTER TABLE `tbluser`
  ADD PRIMARY KEY (`idUser`),
  ADD KEY `tbluser_ibfk_1` (`regId`);


ALTER TABLE `tbluserrole`
  ADD PRIMARY KEY (`idUserRole`),
  ADD KEY `fk_roleId` (`roleId`),
  ADD KEY `fk_userId1` (`userId`);


ALTER TABLE `dimcandidatestatus`
  MODIFY `idCandidateStatus` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;


ALTER TABLE `dimcandiposstatus`
  MODIFY `idCandiPosStatus` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;


ALTER TABLE `dimcertification`
  MODIFY `idCertification` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=10;


ALTER TABLE `dimdept`
  MODIFY `idDept` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;


ALTER TABLE `dimdesignation`
  MODIFY `idDesignation` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;


ALTER TABLE `dimelement`
  MODIFY `idElement` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;


ALTER TABLE `dimelementtype`
  MODIFY `idElementType` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;


ALTER TABLE `diminteractionstatus`
  MODIFY `idinteractionStatus` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;


ALTER TABLE `diminterviewtype`
  MODIFY `idInterviewType` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=9;


ALTER TABLE `dimnontechskill`
  MODIFY `idNonTechSkill` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=12;


ALTER TABLE `dimopstatus`
  MODIFY `idOpStatus` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;


ALTER TABLE `dimposition`
  MODIFY `idPosition` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;


ALTER TABLE `dimqualification`
  MODIFY `idQuali` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=10;


ALTER TABLE `dimrole`
  MODIFY `idRole` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;


ALTER TABLE `dimskill`
  MODIFY `idSkill` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=11;


ALTER TABLE `dimstatus`
  MODIFY `idStatus` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;


ALTER TABLE `tblActivefeedback`
  MODIFY `idActivefeedback` int(11) NOT NULL AUTO_INCREMENT;


ALTER TABLE `tblcadiskill`
  MODIFY `idCandiSkill` int(11) NOT NULL AUTO_INCREMENT;


ALTER TABLE `tblcandidate`
  MODIFY `idCandidate` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=10;


ALTER TABLE `tblcandiquali`
  MODIFY `idCandiQuali` int(11) NOT NULL AUTO_INCREMENT;


ALTER TABLE `tblcertification`
  MODIFY `idCertificationtbl` int(11) NOT NULL AUTO_INCREMENT;


ALTER TABLE `tblconfigure`
  MODIFY `idConfigure` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;


ALTER TABLE `tblelements`
  MODIFY `idElement` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=22;


ALTER TABLE `tblinteraction`
  MODIFY `idRRSchedule` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=36;


ALTER TABLE `tblinteractionfeedback`
  MODIFY `idRRCandidateFeedback` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=15;


ALTER TABLE `tblInterviewer`
  MODIFY `idInterview` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=44;


ALTER TABLE `tbljdquali`
  MODIFY `idJDQuali` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=15;


ALTER TABLE `tbljdskill`
  MODIFY `idJDSkill` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=16;


ALTER TABLE `tbljobdesc`
  MODIFY `idJobDesc` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;


ALTER TABLE `tblloginhist`
  MODIFY `idLoginHist` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=448;


ALTER TABLE `tblnontechskill`
  MODIFY `idNonTechSkilltbl` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=20;


ALTER TABLE `tblopening`
  MODIFY `idOpening` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=26;


ALTER TABLE `tblregistration`
  MODIFY `idReg` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;


ALTER TABLE `tblrolepermissions`
  MODIFY `idRollPerm` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=38;


ALTER TABLE `tblrr`
  MODIFY `idRR` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;


ALTER TABLE `tblrrcandidate`
  MODIFY `idRRCandidate` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;


ALTER TABLE `tbluser`
  MODIFY `idUser` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;


ALTER TABLE `tbluserrole`
  MODIFY `idUserRole` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;


ALTER TABLE `tblActivefeedback`
  ADD CONSTRAINT `FK_tblActivefeedback` FOREIGN KEY (`CandidateRRId`) REFERENCES `tblrrcandidate` (`idRRCandidate`);


ALTER TABLE `tblcadiskill`
  ADD CONSTRAINT `fk_tblcadiskillCandidateId` FOREIGN KEY (`CandidateId`) REFERENCES `tblcandidate` (`idCandidate`),
  ADD CONSTRAINT `fk_tblcadiskillSkillId` FOREIGN KEY (`SkillId`) REFERENCES `dimskill` (`idSkill`);


ALTER TABLE `tblcandidate`
  ADD CONSTRAINT `fk_tblcandidateCandidateStatusId` FOREIGN KEY (`CandidateStatusId`) REFERENCES `dimcandidatestatus` (`idCandidateStatus`);


ALTER TABLE `tblcandiquali`
  ADD CONSTRAINT `fk_tblcandiqualiCandidateId` FOREIGN KEY (`CandidateId`) REFERENCES `tblcandidate` (`idCandidate`),
  ADD CONSTRAINT `fk_tblcandiqualiQualiId` FOREIGN KEY (`QualiId`) REFERENCES `dimqualification` (`idQuali`);


ALTER TABLE `tblcertification`
  ADD CONSTRAINT `fk_tblcertificationCandidateId` FOREIGN KEY (`CandidateId`) REFERENCES `tblcandidate` (`idCandidate`),
  ADD CONSTRAINT `fk_tblcertificationCertificationId` FOREIGN KEY (`CertificationId`) REFERENCES `dimcertification` (`idCertification`);


ALTER TABLE `tblelements`
  ADD CONSTRAINT `fk_tblelementselementTypeId` FOREIGN KEY (`elementTypeId`) REFERENCES `dimelementtype` (`idElementType`);


ALTER TABLE `tblinteraction`
  ADD CONSTRAINT `FK_tblrrscheduleCandidateId` FOREIGN KEY (`CandidateRRId`) REFERENCES `tblrrcandidate` (`idRRCandidate`),
  ADD CONSTRAINT `fk_tblrrscheduleInterviewTypeId` FOREIGN KEY (`InterviewTypeId`) REFERENCES `diminterviewtype` (`idInterviewType`),
  ADD CONSTRAINT `fk_tblrrscheduleinteractionStatusId` FOREIGN KEY (`interactionStatusId`) REFERENCES `diminteractionstatus` (`idinteractionStatus`);


ALTER TABLE `tblinteractionfeedback`
  ADD CONSTRAINT `FK_tblInteractionFeedbackRegId` FOREIGN KEY (`RegId`) REFERENCES `tblregistration` (`idReg`),
  ADD CONSTRAINT `FK_tblInteractionFeedbackScheduleRRId` FOREIGN KEY (`ScheduleRRId`) REFERENCES `tblinteraction` (`idRRSchedule`);


ALTER TABLE `tblInterviewer`
  ADD CONSTRAINT `FK_tblInterviewerRRScheduleId` FOREIGN KEY (`RRScheduleId`) REFERENCES `tblinteraction` (`idRRSchedule`),
  ADD CONSTRAINT `FK_tblInterviewerRegId` FOREIGN KEY (`RegId`) REFERENCES `tblregistration` (`idReg`);


ALTER TABLE `tbljdquali`
  ADD CONSTRAINT `fk_tbljdqualiJobDescId` FOREIGN KEY (`JobDescId`) REFERENCES `tbljobdesc` (`idJobDesc`),
  ADD CONSTRAINT `fk_tbljdqualiSkillId` FOREIGN KEY (`QualiId`) REFERENCES `dimqualification` (`idQuali`);


ALTER TABLE `tbljdskill`
  ADD CONSTRAINT `fk_tbljdskillJobDescId` FOREIGN KEY (`JobDescId`) REFERENCES `tbljobdesc` (`idJobDesc`),
  ADD CONSTRAINT `fk_tbljdskillSkillId` FOREIGN KEY (`SkillId`) REFERENCES `dimskill` (`idSkill`);


ALTER TABLE `tbljobdesc`
  ADD CONSTRAINT `fk_PositionId` FOREIGN KEY (`PositionId`) REFERENCES `dimposition` (`idPosition`);


ALTER TABLE `tblloginhist`
  ADD CONSTRAINT `fk_userId` FOREIGN KEY (`userId`) REFERENCES `tbluser` (`idUser`);


ALTER TABLE `tblnontechskill`
  ADD CONSTRAINT `fk_tblnontechskillCandidateId` FOREIGN KEY (`CandidateId`) REFERENCES `tblcandidate` (`idCandidate`),
  ADD CONSTRAINT `fk_tblnontechskilldimNonTechSkillId` FOREIGN KEY (`dimNonTechSkillId`) REFERENCES `dimnontechskill` (`idNonTechSkill`);


ALTER TABLE `tblopening`
  ADD CONSTRAINT `fk_tblopeningOpStatusId` FOREIGN KEY (`OpStatusId`) REFERENCES `dimopstatus` (`idOpStatus`),
  ADD CONSTRAINT `fk_tblopeningRRId` FOREIGN KEY (`RRId`) REFERENCES `tblrr` (`idRR`);


ALTER TABLE `tblregistration`
  ADD CONSTRAINT `fk_DeptId` FOREIGN KEY (`DeptId`) REFERENCES `dimdept` (`idDept`),
  ADD CONSTRAINT `fk_DesignationId` FOREIGN KEY (`DesignationId`) REFERENCES `dimdesignation` (`idDesignation`);


ALTER TABLE `tblrolepermissions`
  ADD CONSTRAINT `fk_elementId` FOREIGN KEY (`elementId`) REFERENCES `tblelements` (`idElement`),
  ADD CONSTRAINT `fk_roleId1` FOREIGN KEY (`roleId`) REFERENCES `dimrole` (`idRole`);


ALTER TABLE `tblrr`
  ADD CONSTRAINT `fk_JobDescId` FOREIGN KEY (`JobDescId`) REFERENCES `tbljobdesc` (`idJobDesc`),
  ADD CONSTRAINT `fk_Status` FOREIGN KEY (`Status`) REFERENCES `dimstatus` (`idStatus`),
  ADD CONSTRAINT `fk_tblrrDeptId` FOREIGN KEY (`DeptId`) REFERENCES `dimdept` (`idDept`);


ALTER TABLE `tblrrcandidate`
  ADD CONSTRAINT `FK_tblrrcandidateRRId` FOREIGN KEY (`RRId`) REFERENCES `tblrr` (`idRR`),
  ADD CONSTRAINT `fk_tblrrcandidateCandiPosStatusId` FOREIGN KEY (`CandiPosStatusId`) REFERENCES `dimcandiposstatus` (`idCandiPosStatus`),
  ADD CONSTRAINT `fk_tblrrcandidateCandidateId` FOREIGN KEY (`CandidateId`) REFERENCES `tblcandidate` (`idCandidate`),
  ADD CONSTRAINT `fk_tblrrcandidatePositionId` FOREIGN KEY (`PositionId`) REFERENCES `dimposition` (`idPosition`);


ALTER TABLE `tbluser`
  ADD CONSTRAINT `tbluser_ibfk_1` FOREIGN KEY (`regId`) REFERENCES `tblregistration` (`idReg`);


ALTER TABLE `tbluserrole`
  ADD CONSTRAINT `fk_roleId` FOREIGN KEY (`roleId`) REFERENCES `dimrole` (`idRole`),
  ADD CONSTRAINT `fk_userId1` FOREIGN KEY (`userId`) REFERENCES `tbluser` (`idUser`);

INSERT INTO `tblconfigure` (`idConfigure`, `searchKey`, `searchValue`, `IsActive`) VALUES (NULL, 'AttachedFileType', 'txt,doc,docx,pdf', NULL);

