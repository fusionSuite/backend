my Application: FusionSuite
  * access by: 'web' => 443
  * access by: 'admin' => 444


cmdbitems
cmdbproperties
cmdbrelationships

application FusionSuite:
  * logical
  * composed of:
    * Switch 1
      * gi0/1
        * server app1
          * php-fpm (logical)
          * nginx (logical)
    * switch 2
      * gi0/18
        * server db2
          * MariaDB
            * fusionsuite (database)


items
  * id
  * name
  * type_id : customizable (ex: server, conffolder)
  * owner_user_id
  * owner_group_id
  * state_id

itemstates
  * id
  * name
  * created_at
  * updated_at

types
  * id
  * name
  * created_at
  * updated_at
tree?


type_property
  * id
  * type_id
  * property_id


properties
  * id
  * name
  * valuetype (string, integer, float, date, datetime, list)
  * unit
  * created_at
  * updated_at


propertylists (for valuetype = 'list')
  * id
  * propety_id
  * value
  * created_at
  * updated_at


itemproperties
  * id
  * items_id
  * properties_id
  * value
  * created_at
  * updated_at


Relationships:

item_item
  * id
  * parent_item_id
  * child_item_id
  * relationshiptype_id
  * design: logical | physical
  * propagate true/false (set true to propage to sub/children physical item)
  * internal: true | false (for physical relationship for physical items)
  * master_id: (if null, it's master)

ex:
    "parent"
    "child"
    "Is a component of"
    "Is associated with"
    "Uses"
    "Is a new version of"
    "Will be replaced by", …
    ... including relationships to services supported by the CI.

The relationship of the CI with all CIs other than
'parent' and 'child' (e.g. this CI 'uses' another CI, this
CI 'is connected to' another CI, this CI is 'resident on'
another CI, this CI 'can access' another CI).

The relationship of the CI with other CIs
...uses ...
....is connected to........
....is resident on....
....can connect to....
.....is able to access....


Notes : 

  * types de relations customisables
  * some relation can be applied/propaged to all physical child items (like location, organisation...)
  * have 'design|schema': logicial | physical for items and relationtype
  * for physical, define if internal or external relationship
  * a relationship can have properties? 



# FusionInventory

Make a mapping between nodes of XML and type and relationship.

ex:

```
  <CONTENT>
    <ACCESSLOG>
      <LOGDATE>2020-07-19 23:03:24</LOGDATE>
    </ACCESSLOG>
    <BATTERIES>                                                   item, type 'battery'
      <CAPACITY>55000</CAPACITY>                                    property 'capacity'
      <CHEMISTRY>LION</CHEMISTRY>                                   property 'chemistry'
      <DATE>29/08/2019</DATE>
      <MANUFACTURER>Samsung</MANUFACTURER>                          property 'manufacturer'
      <NAME>DELL 1W2Y2EC</NAME>                                   item.name
      <SERIAL>3530</SERIAL>                                         property 'serialnumber'
      <VOLTAGE>7600</VOLTAGE>                                       property 'voltage'
    </BATTERIES>
    <BATTERIES>
      <CAPACITY>55001</CAPACITY>
      <CHEMISTRY>LION</CHEMISTRY>
      <NAME>DELL 1W2Y2EC</NAME>
      <SERIAL>13616</SERIAL>
      <VOLTAGE>7600</VOLTAGE>
    </BATTERIES>
    <BIOS>                                                        item, type 'bios'
      <ASSETTAG>DCS004564</ASSETTAG>
      <BDATE>08/20/2018</BDATE>
      <BMANUFACTURER>Dell Inc.</BMANUFACTURER>
      <BVERSION>1.20.3</BVERSION>
      <MMANUFACTURER>Dell Inc.</MMANUFACTURER>
      <MMODEL>0T6HHJ</MMODEL>
      <MSN>/G39GRF2/CN129636CO06B2/</MSN>
      <SKUNUMBER>06DC</SKUNUMBER>
      <SMANUFACTURER>Dell Inc.</SMANUFACTURER>
      <SMODEL>Latitude E7470</SMODEL>
      <SSN>G39GRF2</SSN>
    </BIOS>
    <CPUS>                                                        item, type 'processor'
      <CORE>2</CORE>                                                property 'number_cores'
      <EXTERNAL_CLOCK>100</EXTERNAL_CLOCK>
      <FAMILYNAME>Core i7</FAMILYNAME>                              property 'familyname'
      <FAMILYNUMBER>6</FAMILYNUMBER>                                property 'familynumber'
      <ID>E3 06 04 00 FF FB EB BF</ID>
      <MANUFACTURER>Intel(R) Corporation</MANUFACTURER>             property 'manufacturer'
      <MODEL>78</MODEL>                                             property 'model'
      <NAME>Core i7</NAME>                                        item.name
      <SERIAL>To Be Filled By O.E.M.</SERIAL>                       property 'serialnumber'
      <SPEED>2600</SPEED>                                           property 'speed' in MHz
      <STEPPING>3</STEPPING>
      <THREAD>4</THREAD>                                            property 'number_threads'
    </CPUS>
    <HARDWARE>
      <CHASSIS_TYPE>Laptop</CHASSIS_TYPE>                         item, type 'laptop' defined by rules
      <CHECKSUM>131071</CHECKSUM>
      <DATELASTLOGGEDUSER>Sun Jul 19 23:03</DATELASTLOGGEDUSER>
      <DESCRIPTION>amd64/-1-11-30 10:05:55</DESCRIPTION>
      <DNS>8.8.8.8</DNS>
      <ETIME>3</ETIME>
      <IPADDR>192.168.20.179</IPADDR>
      <LASTLOGGEDUSER>ddurieux</LASTLOGGEDUSER>
      <MEMORY>8041</MEMORY>
      <NAME>portable</NAME>
      <OSCOMMENTS>FreeBSD 12.1-RELEASE-p1 GENERIC </OSCOMMENTS>
      <OSNAME>freebsd</OSNAME>
      <OSVERSION>12.1-RELEASE-p1</OSVERSION>
      <PROCESSORN>1</PROCESSORN>
      <PROCESSORS>2600</PROCESSORS>
      <PROCESSORT>Core i7</PROCESSORT>
      <SWAP>10240</SWAP>
      <USERID>ddurieux</USERID>
      <UUID>4c4c4544-0033-3910-8047-c7c04f524632</UUID>
      <VMSYSTEM>Physical</VMSYSTEM>
      <WORKGROUP>ddurieux</WORKGROUP>
    </HARDWARE>
    <OPERATINGSYSTEM>
      <BOOT_TIME>2020-07-19 09:09:21</BOOT_TIME>
      <DNS_DOMAIN />  <FQDN />  <FULL_NAME>freebsd</FULL_NAME>
      <KERNEL_NAME>freebsd</KERNEL_NAME>
      <KERNEL_VERSION>12.1-RELEASE-p1</KERNEL_VERSION>
      <NAME>freebsd</NAME>
      <SSH_KEY>ssh-rsa AAAAB3NzaC1yc2EAAAADAQABAAABAQCmD+uIUwZqRkyoRwKZnYFqSSlHga8Zlyy9XdjrFcytC1lkYy/TvsrE355i2pul2XiSFEuM/rkhtlNZX7F0AqKWUhab+Q44E90kxogJnMC
      <TIMEZONE>
        <NAME>CEST</NAME>
        <OFFSET>+0200</OFFSET>
      </TIMEZONE>
      <VERSION>12.1-RELEASE-p1</VERSION>
    </OPERATINGSYSTEM>
    <SOFTWARES>
      <COMMENTS>Fast image processing tools based on ImageMagick</COMMENTS>
      <NAME>GraphicsMagick</NAME>
      <VERSION>1.3.35,1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Image processing tools (legacy version)</COMMENTS>
      <NAME>ImageMagick6</NAME>
      <VERSION>6.9.11.6,1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>High-performance CORBA ORB with support for the C language</COMMENTS>
      <NAME>ORBit2</NAME>
      <VERSION>2.14.19_2</VERSION>
    </SOFTWARES>
    <MEMORIES>
      <CAPACITY>8192</CAPACITY>
      <CAPTION>DIMM A</CAPTION>
      <DESCRIPTION>SODIMM</DESCRIPTION>
      <MANUFACTURER>Micron</MANUFACTURER>
      <MEMORYCORRECTION>None</MEMORYCORRECTION>
      <NUMSLOTS>1</NUMSLOTS>
      <SERIALNUMBER>12161215</SERIALNUMBER>
      <SPEED>2133</SPEED>
      <TYPE>DDR4</TYPE>
    </MEMORIES>
    <NETWORKS>
      <DESCRIPTION>em0</DESCRIPTION>
      <MACADDR>d4:81:d7:5a:a6:43</MACADDR>
      <MTU>1500</MTU>
      <STATUS>Up</STATUS>
      <TYPE>ethernet</TYPE>
      <VIRTUALDEV>0</VIRTUALDEV>
    </NETWORKS>
    <NETWORKS>
      <DESCRIPTION>lo0</DESCRIPTION>
      <IPADDRESS>127.0.0.1</IPADDRESS>
      <IPMASK>255.0.0.0</IPMASK>
      <IPSUBNET>127.0.0.0</IPSUBNET>
      <MTU>16384</MTU>
      <STATUS>Up</STATUS>
      <TYPE>loopback</TYPE>
      <VIRTUALDEV>1</VIRTUALDEV>
    </NETWORKS>
    <NETWORKS>
      <DESCRIPTION>wlan0</DESCRIPTION>
      <IPADDRESS>192.168.20.179</IPADDRESS>
      <IPMASK>255.255.255.0</IPMASK>
      <IPSUBNET>192.168.20.0</IPSUBNET>
      <MACADDR>34:f3:9a:eb:37:eb</MACADDR>
      <MTU>1500</MTU>
      <STATUS>Up</STATUS>
      <TYPE>wifi</TYPE>
      <VIRTUALDEV>0</VIRTUALDEV>
      <WIFI_BSSID>50:64:2b:5c:3b:69</WIFI_BSSID>
      <WIFI_SSID>wd4</WIFI_SSID>
      <WIFI_VERSION>802.11g</WIFI_VERSION>
    </NETWORKS>
  </CONTENT>
```



``` Switch with components
FIRMWARES => [
{
  NAME         => '1812/1812J',
  DESCRIPTION  => 'device firmware',
  TYPE         => 'device',
  VERSION      => '12.4(15)T11, RELEASE SOFTWARE (fc2)',
  MANUFACTURER => 'Cisco',
},
],
COMPONENTS => {
COMPONENT => [
  {
      INDEX            => '1',
      NAME             => 'chassis',
      DESCRIPTION      => '1812 chassis',
      SERIAL           => 'FCZ151292B8',
      MODEL            => 'CISCO1812/K9',
      TYPE             => 'chassis',
      FRU              => '2',
      MANUFACTURER     => 'Cisco',
      FIRMWARE         => 'System Bootstrap, Version 12.3(8r)YH13, RELEASE SOFTWARE (fc1)',
      REVISION         => 'V08',
      VERSION          => '12.4(15)T11, RELEASE SOFTWARE (fc2)',
      CONTAINEDININDEX => '0',
  },
  {
      INDEX            => '2',
      NAME             => 'chassis slot',
      DESCRIPTION      => '1812 chassis slot',
      TYPE             => 'container',
      FRU              => '2',
      MANUFACTURER     => 'Cisco',
      CONTAINEDININDEX => '1',
  },
  {
      INDEX            => '3',
      NAME             => 'motherboard',
      DESCRIPTION      => '1800 Motherboard',
      SERIAL           => 'FCZ151292B8',
      TYPE             => 'module',
      FRU              => '2',
      MANUFACTURER     => 'Cisco',
      REVISION         => 'V08',
      CONTAINEDININDEX => '2',
  },
  {
      INDEX            => '4',
      NAME             => 'BRI0',
      DESCRIPTION      => 'BRI with S/T interface',
      TYPE             => 'port',
      FRU              => '2',
      MANUFACTURER     => 'Cisco',
      CONTAINEDININDEX => '3',
  },
  {
      INDEX            => '5',
      NAME             => 'FastEthernet0',
      DESCRIPTION      => 'PQ3_TSEC',
      TYPE             => 'port',
      FRU              => '2',
      MANUFACTURER     => 'Cisco',
      CONTAINEDININDEX => '3',
  },
```


REFLEXIONS DU 02/08/2020

  <CPUS>                                                        item, type 'processor'
    <CORE>2</CORE>                                                property 'number_cores'
    <EXTERNAL_CLOCK>100</EXTERNAL_CLOCK>
    <FAMILYNAME>Core i7</FAMILYNAME>                              property 'familyname'
    <FAMILYNUMBER>6</FAMILYNUMBER>                                property 'familynumber'
    <ID>E3 06 04 00 FF FB EB BF</ID>
    <MANUFACTURER>Intel(R) Corporation</MANUFACTURER>             property 'manufacturer'
    <MODEL>78</MODEL>                                             property 'model'
    <NAME>Core i7</NAME>                                        item.name
    <SERIAL>To Be Filled By O.E.M.</SERIAL>                       property 'serialnumber'
    <SPEED>2600</SPEED>                                           property 'speed' in MHz
    <STEPPING>3</STEPPING>
    <THREAD>4</THREAD>                                            property 'number_threads'
  </CPUS>

node /CPU                  => item.type = 'Processor'
node /CPU, property CORE   => item.property. id=xx (core),          value
node /CPU, property SERIAL => item.property. id=xx (serial number), value

  <NETWORKS>
    <DESCRIPTION>wlan0</DESCRIPTION>
    <IPADDRESS>192.168.20.179</IPADDRESS>
    <IPMASK>255.255.255.0</IPMASK>
    <IPSUBNET>192.168.20.0</IPSUBNET>
    <MACADDR>34:f3:9a:eb:37:eb</MACADDR>
    <MTU>1500</MTU>
    <STATUS>Up</STATUS>
    <TYPE>wifi</TYPE>
    <VIRTUALDEV>0</VIRTUALDEV>
    <WIFI_BSSID>50:64:2b:5c:3b:69</WIFI_BSSID>
    <WIFI_SSID>wd4</WIFI_SSID>
    <WIFI_VERSION>802.11g</WIFI_VERSION>
  </NETWORKS>

node /NETWORKS           => item.type = 'Network port'
'master'

node /NETWORKS           => item.type = 'IPv4 address'
'sub'

# fusioninventory
id
querytype = computer (inventory)
node      = /CPU
fusioninventory_id (parent)


# fusioninventory properties
id
fusioninventory_id
node        = /CPU
property    = NODE
property_id = xx



## TODO for FusionInventory

Todo list of task remain to code

 * finish create items types and mapping fusioninventory
 * manage items and properties filled by fusioninventory and filled manually
 * check for type of items. some items like operating system, softwares are logical and can be connected by many computers / laptop, servers... must check too the relationship
 * manage relationships between elements
 * manage data in DB to update properties. Need have criteria to find it
 * manage rules to get the right data (begin for example with softwares)
 * add usbid, pciid and oui data



and of course **tests** ;)








# Rules engine

Perhaps use rules with:
  * https://github.com/uuf6429/rune             | very old, last release 2016
  * https://github.com/nicoSWD/php-rule-parser  | javascript criteria, perhaps not enougth for regex extraction
  * https://github.com/bobthecow/ruler          | Seems very old release 2014 (but with code 2018)
  * [++] https://github.com/hoaproject/Ruler    | seems very usefull
  * https://github.com/K-Phoen/rulerz           | seems complex


## Cases to use

Cases where use rules:

  * search item with criteria
    * search item yet in database (fusioninventory engine only)

  * pre-update
    * rewrite fields (hook between save and write in database)

  * post-update
    * Notifications (for all criteria : items, tickets...) (hook after write in database)


## DB Structure

Rule example:

  * (group in ["customer", "guest"] and points > 30) or (group = "test2") or (points = 0)

In JSON:
```
{
  "operator": "or",
  "rules": [
    {
      "field": "group",
      "comparator": "in",
      "values": ["customer", "guest"]
    },
    {
      "field": "points",
      "comparator": ">",
      values: [30]
    },
    {
      "operator": "or",
      "rules": [      
        ...
      ]
    }
  ]
}
```
Perhaps use https://zebzhao.github.io/Angular-QueryBuilder/demo/ structure


Couple examples of rules in backend:

  * item.name = "toto"
  * "item.property.Serial number" = "dwioejf488t3fdwH"
  * item.type = "laptop"
  * ticket.name = "laptop"
  * ticket.category = "blabla"
  



actions:

  * item.name replace "tti"
  * item.name append "to"
  * not import
  * send_notification: {
    "notification": xxx
    recipients: ["dédé", "gégé"]
  }


API must give list of criteria, comparators...


List of tables:
  
  * rule
  * rule_criteria
  * rule_actions



## Cache
  rules can be serialized in cache to enhance performances, the documentation about it is available here: https://hoa-project.net/En/Literature/Hack/Ruler.html#Performances



# TODO

Ajouter option pour notification sur expiration date of property


# Notifications

Rule send notification -> user (and account user have all possibilities to receive like mail, slack, web push...)




=================
02 January 2020

property -> link to propertygroup
                    -> name, position (position ot display of the group in the item)
         -> add field position (position to display of the property in the group)

