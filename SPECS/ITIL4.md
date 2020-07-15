
## Specs

Categories list :

### Architecture Management

The practice of providing an understanding of all the different elements that make up an organization and how those elements relate to one another.

### Information Security Management

The practice of protecting an organization by understanding and managing risks to the confidentiality, integrity, and availability of information. The required security is established by means of policies, processes, behaviors, risk management, and controls, which must maintain a balance between Prevention – Ensuring that security incidents don’t occur, Detection – Rapidly and reliably detecting incidents that can’t be prevented, and Correction – Recovering from incidents after they are detected. It is also important to achieve a balance between protecting the organization from harm and allowing it to innovate. Information security controls that are too restrictive may do more harm than good or may be circumvented by people trying to do work more easily. Information security controls should consider all aspects of the organization and align with its risk appetite.

### Knowledge Management

The practice of maintaining and improving the effective, efficient, and convenient use of information and knowledge across an organization.


### Measurement and Reporting

The practice of supporting good decision-making and continual improvement by decreasing levels of uncertainty.

### Service Financial Management

The practice of supporting an organization’s strategies and plans for service management by ensuring that the organization’s financial resources and investments are being used effectively.

### Supplier Management

This practice is concerned with ensuring the organization’s suppliers and their performance are managed appropriately to support the seamless provision of quality products and services. Activities that are central to the supplier management practice include:

* Creating a single point of visibility and control to ensure consistency
* Maintaining a supplier strategy, policy, and contract management information
* Negotiating and agreeing contracts and arrangements
* Managing relationships and contracts with internal and external suppliers
* Managing supplier performance

### Availability Management

The practice of ensuring that services deliver agreed levels of availability to meet the needs of customers and users.

### Business Analysis

The practice of analyzing a business or some element of a business, defining its needs and recommending solutions to address these needs and/or solve a business problem, and create value for stakeholders.

### Capacity and Performance Management

The practice of ensuring that services achieve agreed and expected performance levels, satisfying current and future demand in a cost-effective way.

### Change Enablement

The practice of ensuring that risks are properly assessed, authorizing changes to proceed and managing a change schedule in order to maximize the number of successful service and product changes.

### Incident Management

This practice is concerned with minimizing the negative impact of incidents by restoring normal service operation as quickly as possible. Incident management can have an enormous impact on customer and user satisfaction, and on how customers and users perceive the service provider. Every incident should be logged and managed to ensure that it is resolved in a time that meets the expectations of the customer and user. Target resolution times are agreed, documented, and communicated to ensure that expectations are realistic. Incidents are prioritized based on an agreed classification to ensure that incidents with the highest business impact are resolved first. Organizations should design their incident management practice to provide appropriate management and resource allocation to different types of incidents. Incidents with a low impact must be managed efficiently to ensure that they do not consume too many resources. Incidents with a larger impact may require more resources and more complex management.There are usually separate processes for managing major incidents, and for managing information security incidents. As with ITIL v3, the concept of a “Major Incident” is included in the ITIL 4 material and this term is defined as: Major Incident: The highest category of impact for an incident. A major incident results in significant disruption to the business. Major incidents have their own procedure with shorter timeframes, when compared to day-to-day incidents, and will often invoke an organization’s disaster recovery/service continuity management activities.

url: https://wiki.en.it-processmaps.com/index.php/Incident_Management

###  IT Asset Management

This practice is concerned with planning and managing the full lifecycle of IT assets. The scope of IT asset management typically includes all software, hardware, networking, cloud services, and client devices. In some cases, it may also include non-IT assets such as buildings or information where these items have a financial value and are required to deliver an IT service. IT asset management can include operational technology (OT), including devices that are part of the Internet of Things. These are typically devices that were not traditionally thought of as IT assets, but that now include embedded computing capability and network connectivity. Understanding the cost and value of assets is essential to also comprehending the cost and value of products and services and is therefore an important underpinning factor in everything the service provider does. IT asset management contributes to the visibility of assets and their value, which is a key element to successful service management as well as being useful to other practices. The ITIL v3 process named Service Asset and Configuration Management was separated into two ITIL 4 Practices – IT Asset Management and Service Configuration Management, which will be detailed further below.

### Monitoring and Event Management

The purpose of this practice is to systematically observe services and service components, and record and report selected changes of state identified as events. The monitoring and event management practice manages events throughout their lifecycle to prevent, minimize, or eliminate their negative impact on the business. Monitoring and event management helps to identify and prioritize infrastructure, services, business processes, and information security events, and establishes the appropriate response to those events, including responding to conditions that could lead to potential faults or incidents. The monitoring part of the practice focuses on the systematic observation of services and the CIs that underpin services to detect conditions of potential significance. Monitoring should be performed in a highly automated manner and can be done actively or passively. The event management part focuses on recording and managing those monitored changes of state that are defined by the organization as an event, determining their significance, and identifying and initiating the correct control action to manage them. Frequently the correct control action will be to initiate another practice, but sometimes it will be to take no action other than to continue monitoring the situation. Monitoring is necessary for event management to take place, but not all monitoring results in the detection of an event. Not all events have the same significance or require the same response. Events are often classified as informational, warning, and exceptions. Informational events do not require action at the time they are identified, but analyzing the data gathered from them later may uncover desirable, proactive steps that can be beneficial to the service. Warning events allow action to be taken before any negative impact is experienced by the business, whereas exception events indicate that a breach to an established norm has been identified (for example, to a service level agreement). Exception events require action, even though business impact may not yet have been experienced.

### Problem Management

This practice is concerned with reducing the likelihood and impact of incidents by identifying actual and potential causes of incidents and managing workarounds and known errors. Every service has errors, flaws, or vulnerabilities that may cause incidents. They may include errors in any of the four dimensions of service management. Many errors are identified and resolved before a service goes live. However, some remain unidentified or unresolved, and may be a risk to live services. In ITIL, these errors are called problems and they are addressed by the problem management practice. Problems are related to incidents, but should be distinguished as they are managed in different ways:

* Incidents have an impact on users or business processes and must be resolved so that normal business activity can take place.
* Problems are the causes of incidents. They require investigation and analysis to identify the causes, develop workarounds, and recommend longer-term resolution. This reduces the number and impact of future incidents.

In the problem management practice, there are three phases that generally take place as shown below.

Problem identification --> problem control --> error control

### Release Management

This practice is focused on making new and changed services and features available for use. A release may comprise many different infrastructure and application components that work together to deliver new or changed functionality. It may also include documentation, training (for users or IT staff), updated processes or tools, and any other components that are required. Each component of a release may be developed by the service provider or procured from a third party and integrated by the service provider. Releases can range in size from the very small, involving just one minor changed feature, to the very large, involving many components that deliver a completely new service. In either case, a release plan will specify the exact combination of new and changed components to be made available, and the timing for their release. A release schedule is used to document the timing for releases. This schedule should be negotiated and agreed with customers and other stakeholders. A release post-implementation review enables learning and improvement and helps to ensure that customers are satisfied. In some environments, almost all the release management work takes place before deployment, with plans in place as to exactly which components will be deployed in a release. The deployment then makes the new functionality available. The ITIL v3 process named Release and Deployment Management was separated into two ITIL 4 Practices – Release Management and Deployment Management, which will be detailed further below.

### Service Catalog Management

The practice of providing a single source of consistent information on all services and service offerings and ensuring that it is available to the relevant audience.

### Service Configuration Management

The purpose of this practice is to ensure that accurate and reliable information about the configuration of services, and the CIs that support them, is available when and where it is needed. Configuration management provides information on the CIs that contribute to each service and their relationships: how they interact, relate, and depend on each other to create value for customers and users. This includes information about dependencies between services. This high-level view is often called a service map or service model, and forms part of the service architecture. It is important that the effort needed to collect and maintain configuration information is balanced with the value that the information creates. Maintaining large amounts of detailed information about every component, and its relationships to other components, can be costly, and may deliver very little value. The requirements for configuration management must be based on an understanding of the organization’s goals, and how configuration management contributes to value creation. In short, the IT Asset Management practice is about understanding “content” (what we have), and the Service Configuration Management practice is about understanding “context” (the relationships between what we have).

### Service Continuity Management

The practice of ensuring that service availability and performance are maintained at a sufficient level in case of a disaster.

### Service Desk

The purpose of this practice is to capture demand for incident resolution and service requests. Service desks provide a clear path for users to report issues, queries, and requests, and have them acknowledged, classified, owned, and actioned. How this practice is managed and delivered may vary from a physical team of people on shift work to a distributed mix of people connected virtually, or automated technology and bots. The function and value of the service desk remain the same, regardless of the model.With increased automation and the gradual removal of technical debt, the focus of the service desk is to provide support for ‘people and business’ rather than simply technical issues. Service desks are increasingly being used to get various matters arranged, explained, and coordinated, rather than just to get broken technology fixed, and the service desk has become a vital part of any service operation. A key point to be understood is that, no matter how efficient the service desk and its people are, there will always be issues that need escalation and underpinning support from other teams. Support and development teams need to work in close collaboration with the service desk to present and deliver a ‘joined up’ approach to users and customers. The service desk may not need to be highly technical, although some are. However, even if responsibility of the service desk is simple, it still plays a vital role in the delivery of services and must be actively supported by its peer groups. It is also essential to understand that the service desk has a major influence on user experience and how the service provider is perceived by users. Another key aspect of a good service desk is its practical understanding of the wider organization, the business processes, and the users. Service desks add value not simply through the transactional acts of, for example, incident logging, but also by understanding and acting on the business context of this action. The service desk should be the empathetic and informed link between the service provider and its users.

### Service Level Management

This practice is focused on setting clear business-based targets for service performance, so that the delivery of a service can be properly assessed, monitored, and managed against these targets. Service level management provides the end-to-end visibility of the organization’s services and helps negotiate and manage performance against Service Level Agreements (SLAs).

### Service Request Management

This practice focuses on supporting the agreed quality of services by handling all pre-defined, user-initiated service requests in an effective and user- friendly manner. Service requests are a normal part of service delivery and are not a failure or degradation of service, which are handled as incidents. Since service requests are pre-defined and pre-agreed as a normal part of service delivery, they can usually be formalized, with a clear, standard procedure for initiation, approval, fulfillment, and management. Service request management is dependent upon well-designed processes and procedures, which are operationalized through tracking and automation tools to maximize the efficiency of the practice. Different types of service request will have different fulfillment workflows, but both efficiency and maintainability will be improved if a limited number of workflow models are identified. When new service requests need to be added to the service catalog, existing workflow models should be leveraged whenever possible.

### Service Validation and Testing

The practice of ensuring that new or changed products and services meet defined requirements.

### Deployment Management

The purpose of this practice is to move new or changed hardware, software, documentation, processes, or any other component to live environments. Deployment management works closely with release management and change control, but it is a separate practice. In some organizations, the term ‘provisioning’ is used to describe the deployment of infrastructure, and deployment is only used to mean software deployment, but in this case the term deployment is used to mean both. In short, the Deployment Management practice is typically an IT decision to move components to live environments, whereas the Release Management practice is typically a business decision to make services and features available for use by customers. These practices can be performed separately as seen within Agile/DevOps environments

### Infrastructure and Platform Management

The practice of overseeing the infrastructure and platforms used by an organization. This enables the monitoring of technology solutions available, including solutions from third parties.

### Software Development and Management

The practice of ensuring that applications meet stakeholder needs in terms of functionality, reliability, maintainability, compliance, and auditability.
