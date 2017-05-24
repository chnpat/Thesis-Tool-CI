-- Database Initialization Script
-- ------------------------------
-- Author: Charnon Pattiyanon
-- Affiliation: Department of Computer Engineering, Chulalongkorn University
-- Created Date: 20-May-2017

-- Create Database Script
CREATE DATABASE IF NOT EXISTS `qam_tool` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `qam_tool`;

--
-- Table structure for table `ci_session`
--
CREATE TABLE IF NOT EXISTS `ci_sessions` (
  `id` varchar(128) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `timestamp` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `data` blob NOT NULL,
  `user_data` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `assess_result`
--

CREATE TABLE IF NOT EXISTS `assess_result` (
  `result_id` int(11) NOT NULL AUTO_INCREMENT,
  `pattern_id` varchar(255) NOT NULL,
  `desc_version` varchar(255) NOT NULL,
  `metric_id` int(11) NOT NULL,
  `score` decimal(10,2) NOT NULL,
  `assessor_id` int(11) NOT NULL,
  PRIMARY KEY (`result_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `assess_result_detail`
--

CREATE TABLE IF NOT EXISTS `assess_result_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `result_id` int(11) NOT NULL,
  `variable_id` int(11) NOT NULL,
  `variable_score` decimal(10,2) NOT NULL,
  `remark` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `metric`
--

CREATE TABLE IF NOT EXISTS `metric` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `metric_name` varchar(255) NOT NULL,
  `metric_abberv` varchar(10) NOT NULL,
  `metric_short_description` text NOT NULL,
  `metric_description` text NOT NULL,
  `metric_QA` varchar(255) NOT NULL,
  `metric_QCP` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `metric`
--

INSERT INTO `metric` (`id`, `metric_name`, `metric_abberv`, `metric_short_description`, `metric_description`, `metric_QA`, `metric_QCP`) VALUES
(1, 'Desired Knowledge in Topics', 'DKT', 'The DKT metric reflects whether each topic in the OODP description contains the desired knowledge.', '<div class=\"text-justify\">&nbsp;The DKT metric reflects whether each topic in the OODP description contains the desired knowledge. The metric considers 13 common topics found in pattern templates in the Design Pattern books, i.e. Pattern Name and Classification, Intent, Also Known As, Motivation, Applicability, Structure,&nbsp;Participants, Collaborations, Consequences, Implementation, Sample Code, Known Uses, and Related Patterns. We extract knowledge about design patterns from the 3 design pattern books&nbsp;and define a set of desired knowledge elements that should be embedded in each of the 13 topics. Altogether there are 33 knowledge elements for all 13 topics. The DKT score can be computed by&nbsp;</div>\r\n\r\n<p><img alt=\"\" src=\"http://localhost:8888/Thesis-Tool/images/Metric/DKT.png\" style=\"width:100%\" /></p>\r\n\r\n<table border=\"1\" cellpadding=\"1\" cellspacing=\"1\" style=\"width:100%\">\r\n <tbody>\r\n   <tr>\r\n      <td style=\"width:10%\">Where</td>\r\n      <td class=\"text-center\" style=\"width:20%\"><var>T</var></td>\r\n     <td>= number of topics expected in pattern description =\r\n13.</td>\r\n    </tr>\r\n   <tr>\r\n      <td>&nbsp;</td>\r\n     <td class=\"text-center\"><var>E</var><sub><var>t</var></sub></td>\r\n      <td>= existence value of topic t in pattern description, i.e. 1 if t exists and 0 otherwise.</td>\r\n   </tr>\r\n   <tr>\r\n      <td>&nbsp;</td>\r\n     <td class=\"text-center\"><var>K</var><sub><var>t</var></sub></td>\r\n      <td>= number of desired knowledge elements in topic <var>t</var>.</td>\r\n    </tr>\r\n   <tr>\r\n      <td>&nbsp;</td>\r\n     <td class=\"text-center\"><var>a<sub>t,k</sub></var></td>\r\n     <td>= existence value of knowledge element <var>k</var> in topic <var>t</var>, i.e. 1 if k exists and 0 otherwise.</td>\r\n   </tr>\r\n   <tr>\r\n      <td>&nbsp;</td>\r\n     <td class=\"text-center\"><var>DKT</var></td>\r\n     <td>is in [0, 1].</td>\r\n    </tr>\r\n </tbody>\r\n</table>\r\n\r\n<div class=\"text-justify\">If DKT is less than 1, some topics are missing from the pattern description or some knowledge elements are missing from relevant topics. The pattern developer should consider revising the pattern accordingly.</div>\r\n<p>&nbsp;</p>\r\n<div class=\"text-justify\"><b>Example:</b> Due to limitation of space, we only show, in Table V, some example of the topics and desired knowledge elements, together with the result of using them to assess the case study design pattern. The total at, k score of the Collection Limitation pattern is 20 out of 33 knowledge elements and the DKT score is 0.74. The pattern developer should consider the assessment result and, for example, decide whether the rationale and forces that cause the problem should be added to the intent topic of the pattern. </div>', 'Embedded Knowledge Quality Attribute', 'Completeness'),
(2, 'ConSistency between Diagrams', 'CSD', 'This CSD metric reflects consistency between diagrams of software models in the case that the pattern contains more than one diagram.', '<div class=\"text-justify\">&nbsp;This CSD metric reflects consistency between diagrams of software models in the case that the pattern contains more than one diagram. This metric considers five types of UML diagrams that are found in design patterns, i.e. Use Case Diagram, Activity Diagram, Class Diagram, Sequence Diagram, and Behavioral State Machine. We apply the verification and validation rules, taken from the SA&D book by Dennis et al. [13], to check for consistency between these types of UML models. Altogether there are 12 rules. The CSD score can be computed by \r\n</div><br/>\r\n\r\n', 'Embedded Knowledge Quality Attribute', 'Consistency'),
(3, 'Pattern Application Proportion', 'PAP', 'This PAP metric reflects usefulness of the structure of the solution, i.e. class diagram, in the pattern. ', '<div class=\"text-justify\">\r\nThis PAP metric reflects usefulness of the structure of the solution, i.e. class diagram, in the pattern. It requires that the pattern be applied in the design of at least one software project so that the proportion of class diagram elements which are actually applied in the design can be determined. Since how the pattern is applied in a real design may depend on the project and the experience of the pattern user who designs the software, it is recommended to use more than one project and more than one system analyst, and compute an average PAP score over a number of projects. The PAP score over a project p can be computed by\r\n</div><br/>', 'Embedded Knowledge Quality Attribute', 'Usefulness'),
(4, 'Content Reading-Ease', 'CRE', 'This CRE metric reflects how easy the textual contents in the pattern description are to read and understand.', '<div class=\"text-justify\">&nbsp;This CRE metric reflects how easy the textual contents in the pattern description are to read and understand. The metric is based on the Flesch Reading-Ease Score [12] which measures text readability and is adapted to compute the average of the reading-ease scores of all topics with textual contents. The CRE score can be computed by<br/>\r\n', 'Pattern Language Quality Attribute', 'Understandability');

-- --------------------------------------------------------

--
-- Table structure for table `metric_variable`
--

CREATE TABLE IF NOT EXISTS `metric_variable` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `metric_id` int(11) NOT NULL,
  `variable_name` varchar(255) NOT NULL,
  `variable_description` text NOT NULL,
  `variable_diagram` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=90 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `metric_variable`
--

INSERT INTO `metric_variable` (`id`, `metric_id`, `variable_name`, `variable_description`, `variable_diagram`) VALUES
(1, 1, 'a<sub>1,1</sub>', '<div class=\"text-justify\"><i>Pattern Name</i> describes the name of the pattern which conveys the essence of the pattern succinctly.</div>', ''),
(2, 1, 'a<sub>1,2</sub>', '<div class=\"text-justify\"><i>Pattern Classification</i> identifies the classification of the pattern which groups up similar patterns together. \r\n</div>', ''),
(3, 1, 'a<sub>2,1</sub>', '<div class=\"text-justify\"><i>Intent</i> describes the intent of the pattern.</div>', ''),
(4, 1, 'a<sub>2,2</sub>', '<div class=\"text-justify\"><i>Intent</i> describes the rationale behind the pattern or the design issue or problem that the pattern intends to solve.</div>', ''),
(5, 1, 'a<sub>2,3</sub>', '<div class=\"text-justify\"><i>Intent</i> discusses the associated forces that cause the problem.</div>', ''),
(6, 1, 'a<sub>3,1</sub>', '<div class=\"text-justify\"><i>Also Known As</i> provides other well-known names for the pattern.</div>', ''),
(7, 1, 'a<sub>4,1</sub>', '<div class=\"text-justify\"><i>Motivation</i> describes at least one scenario that illustrates the design problem.</div>', ''),
(8, 1, 'a<sub>4,2</sub>', '<div class=\"text-justify\"><i>Motivation</i> describes the concrete problem scenario with graphical or diagrammatical notation.</div>', ''),
(9, 1, 'a<sub>4,3</sub>', '<div class=\"text-justify\"><i>Motivation</i> describes how the class, object, graphical representation in the pattern solve the problem.</div>', ''),
(10, 1, 'a<sub>4,4</sub>', '<div class=\"text-justify\"><i>Motivation</i> describes at least one concrete example of the solution (with graphical or diagrammatical notation) in implementation aspect of the real application.</div>', ''),
(11, 1, 'a<sub>5,1</sub>', '<div class=\"text-justify\"><i>Applicability</i> describes the situation in which the design pattern can be applied.</div>', ''),
(12, 1, 'a<sub>5,2</sub>', '<div class=\"text-justify\"><i>Applicability</i> describes the example of poor design that the pattern can address.</div>', ''),
(13, 1, 'a<sub>5,3</sub>', '<div class=\"text-justify\"><i>Applicability</i> describes how the pattern user can recognize the situation for using patterns.</div>', ''),
(14, 1, 'a<sub>6,1</sub>', '<div class=\"text-justify\"><i>Structure</i> provides a graphical representation of classes in the pattern using UML.</div>', ''),
(15, 1, 'a<sub>6,2</sub>', '<div class=\"text-justify\">The class representation provided in <i>Structure</i> is described in an abstract level (Not concrete class structure).</div>', ''),
(16, 1, 'a<sub>6,3</sub>', '<div class=\"text-justify\"><i>Structure</i> describes the sequence of requests and the collaboration of objects of the classes in the pattern using a graphical representation.</div>', ''),
(17, 1, 'a<sub>7,1</sub>', '<div class=\"text-justify\"><i>Participants</i> describes the classes and/or objects participating in the design pattern along with its attributes.</div>', ''),
(18, 1, 'a<sub>7,2</sub>', '<div class=\"text-justify\"><i>Participants</i> describes the responsibilities of each class and/or each object.</div>', ''),
(19, 1, 'a<sub>8,1</sub>', '<div class=\"text-justify\"><i>Collaborations</i> describes how participants collaborate to carry out their responsibilities.</div>', ''),
(20, 1, 'a<sub>9,1</sub>', '<div class=\"text-justify\"><i>Consequences</i> describes how the pattern supports its objectives.</div>', ''),
(21, 1, 'a<sub>9,2</sub>', '<div class=\"text-justify\"><i>Consequences</i> describes the trade-offs of using the pattern.</div>', ''),
(22, 1, 'a<sub>9,3</sub>', '<div class=\"text-justify\"><i>Consequences</i> describes the results of using the pattern.</div>', ''),
(23, 1, 'a<sub>9,4</sub>', '<div class=\"text-justify\"><i>Consequences</i> describes the aspects of the system structure that make the use of the pattern vary independently.</div>', ''),
(24, 1, 'a<sub>10,1</sub>', '<div class=\"text-justify\"><i>Implementation</i> identifies pitfall(s), hint(s), or technique(s) that the pattern user should be aware of when implementing the pattern.</div>', ''),
(25, 1, 'a<sub>10,2</sub>', '<div class=\"text-justify\"><i>Implementation</i> describes whether there are programming-language- specific issues or not.</div>', ''),
(26, 1, 'a<sub>10,3</sub>', '<div class=\"text-justify\"><i>Implementation</i> provides a guideline or recommended steps to implement the pattern.</div>', ''),
(27, 1, 'a<sub>10,4</sub>', '<div class=\"text-justify\"><i>Implementation</i> describes the variants or specialization of the pattern.</div>', ''),
(28, 1, 'a<sub>11,1</sub>', '<div class=\"text-justify\"><i>Sample Code</i> provides code fragments that illustrate how the pattern user might implement the pattern in a well-known object-oriented programming language.</div>', ''),
(29, 1, 'a<sub>11,2</sub>', '<div class=\"text-justify\"><i>Sample Code</i> describes the code fragment in detail where it relates to the classes in the pattern.</div>', ''),
(30, 1, 'a<sub>12,1</sub>', '<div class=\"text-justify\"><i>Known Uses</i> describes at least one example of the pattern found in real systems.</div>', ''),
(31, 1, 'a<sub>13,1</sub>', '<div class=\"text-justify\"><i>Related Patterns</i> describes which other pattern(s) are closely related to this pattern.</div>', ''),
(32, 1, 'a<sub>13,2</sub>', '<div class=\"text-justify\"><i>Related Patterns</i> describes the important differences from other pattern(s).</div>', ''),
(33, 1, 'a<sub>13,3</sub>', '<div class=\"text-justify\"><i>Related Patterns</i> describes how to use this pattern with other pattern(s).</div>', ''),
(47, 2, '#Classes Associated With Any Use Case', '<div class=\"text-justify\">A number of classes in a class diagram that are associated with any use cases in a use case diagram in the pattern description.</div>', 'CD|UCD'),
(48, 2, '#Classes', '<div class=\"text-justify\">A number of classes in a class diagram in the pattern description.</div>', 'CD|UCD'),
(49, 2, '#Object Nodes in AD Associated With a Class', '<div class=\"text-justify\">A number of object nodes in an activity diagram that are associated with a class on a class diagram in the pattern description.</div>', 'AD|CD'),
(50, 2, '#Object Nodes in AD', '<div class=\"text-justify\">A number of object nodes in an activity diagram in the pattern description.</div>', 'AD|CD'),
(51, 2, '#Activities/Actions in AD Associated With Any Operations in CD', '<div class=\"text-justify\">A number of activities or actions in an activity diagram that are associated with any operations on a class diagram in the pattern description.</div>', 'AD|CD'),
(52, 2, '#Activities/Actions in AD', '<div class=\"text-justify\">A number of activities or actions in an activity diagram in the pattern description.</div>', 'AD|CD'),
(53, 2, '#Actors in SD Associated With an Actor in UCD', '<div class=\"text-justify\">A number of actors in a sequence diagram that are associated with an actor in a use case diagram in the pattern description</div>', 'SD|UCD'),
(54, 2, '#Actors in SD', '<div class=\"text-justify\">A number of actors in a sequence diagram in the pattern description</div>', 'SD|UCD'),
(55, 2, '#Messages in SD Associated With Any Activity/Action in AD', '<div class=\"text-justify\">A number of messages in a sequence diagram that are associated with any activity or action in activity diagram in the pattern description.</div>', 'SD|AD'),
(56, 2, '#Messages in SD', '<div class=\"text-justify\">A number of messages in a sequence diagram in the pattern description.</div>', 'SD|AD'),
(57, 2, '#Transitions in BSM Associated With Any Activity/Action in AD', '<div class=\"text-justify\">A number of transitions in a behavioral state machine that are associated with any activity or action in an activity diagram in the pattern description.</div>', 'BSM|AD'),
(58, 2, '#Transitions in BSM', '<div class=\"text-justify\">A number of transitions in a behavioral state machine in the pattern description.</div>', 'BSM|AD'),
(59, 2, '#Complex Object Nodes in AD Having BSM', '<div class=\"text-justify\">A number of complex object nodes in an activity diagram that are having an associated behavioral state machine in the pattern description.</div>', 'AD|BSM'),
(60, 2, '#Complex Object Nodes in AD', '<div class=\"text-justify\">A number of complex object nodes in an activity diagram  in the pattern description.</div>', 'AD|BSM'),
(61, 2, '#BSMs Associated With a Class in CD', '<div class=\"text-justify\">A number of behavioral state machines that are associated with an instance (object) of a class in a class diagram in the pattern description.</div>', 'BSM|CD'),
(62, 2, '#BSMs', '<div class=\"text-justify\">A number of behavioral state machines in the pattern description</div>', 'BSM|CD'),
(63, 2, '#Objects in SD Associated With a Class', '<div class=\"text-justify\">A number of objects in a sequence diagram that are associated with a class in a class diagram in the pattern description.</div>', 'SD|AD'),
(64, 2, '#Objects in SD', '<div class=\"text-justify\">A number of objects in a sequence diagram in the pattern description.</div>', 'SD|AD'),
(65, 2, '#Messages in SD Associated With Any Opr and Assoc in CD', '<div class=\"text-justify\">A number of messages in a sequence diagram that are associated with any operation and/or operations in a class diagram in the pattern description.</div>', 'SD|CD'),
(66, 2, '#Messages in SD', '<div class=\"text-justify\">A number of messages in a sequence diagram in the pattern description</div>', 'SD|CD'),
(67, 2, '#Transitions in BSM Associated With Any Opr and Assoc in CD', '<div class=\"text-justify\">A number of transitions in a behavioral state machine that are associated with any operation and/or association in a class on a class diagram in the pattern description.</div>', 'BSM|CD'),
(68, 2, '#Transitions in BSM', '<div class=\"text-justify\">A number of transitions in a behavioral state machine in the pattern description.</div>', 'BSM|CD'),
(69, 2, '#States in BSM Associated With Any Attribute Value in CD', '<div class=\"text-justify\">A number of states in a behavioral state machine that are associated with any attribute values or a set of attributes in a class on a class diagram in the pattern description.</div>', 'BSM|CD'),
(70, 2, '#States in BSM', '<div class=\"text-justify\">A number of states in a behavioral state machine in the pattern description.</div>', 'BSM|CD'),
(71, 3, '#Classes Implemented', '<div class=\"text-justify\">A number of classes that are implemented following the pattern in the corresponding design in pilot project.</div>', 'CD'),
(72, 3, '#Classes in Pattern', '<div class=\"text-justify\">A number of classes that are specified in the pattern description.</div>', 'CD'),
(73, 3, '#Attributes Implemented', '<div class=\"text-justify\">A number of attributes that are implemented following the pattern in the corresponding design in pilot project.</div>', 'CD'),
(74, 3, '#Attributes in Pattern', '<div class=\"text-justify\">A number of attributes that are specified in the pattern description.</div>', 'CD'),
(75, 3, '#Operations Implemented', '<div class=\"text-justify\">A number of operations that are implemented following the pattern in the corresponding design in pilot project.</div>', 'CD'),
(76, 3, '#Operations in Pattern', '<div class=\"text-justify\">A number of operations that are specified in the pattern description.</div>', 'CD'),
(77, 3, '#Association Relationships Implemented', '<div class=\"text-justify\">A number of association relationships that are implemented following the pattern in the corresponding design in pilot project.</div>', 'CD'),
(78, 3, '#Association Relationships in Pattern', '<div class=\"text-justify\">A number of association relationships that are specified in the pattern description.</div>', 'CD'),
(79, 3, '#Generalization Relationships Implemented', '<div class=\"text-justify\">A number of generalization relationships that are implemented following the pattern in the corresponding design in pilot project.</div>', 'CD'),
(80, 3, '#Generalization Relationships in Pattern', '<div class=\"text-justify\">A number of generalization relationships that are specified in the pattern description.</div>', 'CD'),
(81, 3, '#Aggregation Relationships Implemented', '<div class=\"text-justify\">A number of aggregation relationships that are implemented following the pattern in the corresponding design in pilot project.</div>', 'CD'),
(82, 3, '#Aggregation Relationships in Pattern', '<div class=\"text-justify\">A number of aggregation relationships that are specified in the pattern description.</div>', 'CD'),
(83, 3, '#Composition Relationships Implemented', '<div class=\"text-justify\">A number of composition relationships that are implemented following the pattern in the corresponding design in pilot project.</div>', 'CD'),
(84, 3, '#Composition Relationships in Pattern', '<div class=\"text-justify\">A number of composition relationships that are specified in the pattern description.</div>', 'CD'),
(86, 4, 'N<sub>word</sub>', '<div class=\"text-justify\">A number of words in the corresponding content.</div>', ''),
(87, 4, 'N<sub>sentence</sub>', '<div class=\"text-justify\">A number of sentences in the corresponding content.</div>', ''),
(88, 4, 'N<sub>syllable</sub>', '<div class=\"text-justify\">A number of syllables in the corresponding content.</div>', ''),
(89, 4, 'Topic Score', '<div class=\"text-justify\">A CRE score of the corresponding topic.</div>', '');

-- --------------------------------------------------------

--
-- Table structure for table `pattern`
--

CREATE TABLE IF NOT EXISTS `pattern` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pattern_id` varchar(255) NOT NULL,
  `pattern_name` varchar(255) NOT NULL,
  `pattern_creator_id` int(11) NOT NULL,
  `pattern_assess_version` decimal(10,1) NOT NULL,
  `pattern_assess_limit` int(11) NOT NULL DEFAULT '1',
  `pattern_status` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `pattern_description`
--

CREATE TABLE IF NOT EXISTS `pattern_description` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pattern_id` varchar(255) NOT NULL,
  `desc_version` decimal(10,1) NOT NULL,
  `desc_assess_count` int(11) NOT NULL DEFAULT '0',
  `desc_classification` varchar(255) NOT NULL,
  `desc_aka` varchar(255) NOT NULL,
  `desc_intent` text NOT NULL,
  `desc_motivation` text NOT NULL,
  `desc_applicability` text NOT NULL,
  `desc_structure` text NOT NULL,
  `desc_participants` text NOT NULL,
  `desc_collaborations` text NOT NULL,
  `desc_implementation` text NOT NULL,
  `desc_consequences` text NOT NULL,
  `desc_known_uses` text NOT NULL,
  `desc_sample_code` text NOT NULL,
  `desc_related_pattern` text NOT NULL,
  `is_UCD` tinyint(1) NOT NULL,
  `is_AD` tinyint(1) NOT NULL,
  `is_CD` tinyint(1) NOT NULL,
  `is_SD` tinyint(1) NOT NULL,
  `is_BSM` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user_login`
--

CREATE TABLE IF NOT EXISTS `user_login` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(255) NOT NULL,
  `user_email` varchar(255) NOT NULL,
  `user_password` varchar(255) NOT NULL,
  `user_created_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `user_status` tinyint(1) NOT NULL DEFAULT '0',
  `user_role` varchar(255) NOT NULL DEFAULT 'Regular',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user_login`
--

INSERT INTO `user_login` (`id`, `user_name`, `user_email`, `user_password`, `user_created_date`, `user_status`, `user_role`) VALUES
(1, 'admin', 'admin@qam.com', 'd033e22ae348aeb5660fc2140aec35850c4da997', '2017-01-01 00:00:00', 1, 'Admin');
