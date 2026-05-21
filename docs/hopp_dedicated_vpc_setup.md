# HOPP Dedicated VPC Setup

Focused runbook for separating HOPP infrastructure from the shared `default` VPC before the final production cutover.

---

## Purpose

The existing production VM `hopp-prod` currently lives on the shared `default` VPC/subnet used by another server (`finluybackend`).

To avoid shared-network risk during IPv6 and cutover work, a dedicated HOPP VPC was created first.

This document records exactly what was created and what still needs to happen next.

---

## Created Network

### VPC

- Name: `hopp-vpc`
- Subnet creation mode: `Custom`
- Dynamic routing mode: `Regional`
- Best path selection mode: `Legacy (default)`

### Subnet

- Name: `hopp-subnet`
- Region: `asia-southeast1` (`Singapore`)
- IP stack type: `IPv4 and IPv6 (dual-stack)`
- IPv6 access type: `External`
- IPv4 range: `10.20.0.0/24`
- Private Google Access: `Off`
- Flow logs: `Off`
- Hybrid subnets: `Off`

Notes:

- The current HOPP VM is in zone `asia-southeast1-c`; that is valid because the subnet is regional and covers zones inside `asia-southeast1`.
- `10.20.0.0/24` was chosen as a clean private range that does not overlap with the observed shared default network usage (`10.148.x.x`).

---

## Firewall Rules Created

### IPv4 Web Ingress

- Rule name: `allow-web-traffic`
- Network: `hopp-vpc`
- Direction: `Ingress`
- Action: `Allow`
- Targets: `All instances in the network`
- Source filter: `IPv4 ranges`
- Source IPv4 ranges: `0.0.0.0/0`
- Protocols / ports: `tcp:80,443`

### IPv6 Web Ingress

- Rule name: `allow-web-traffic-ipv6`
- Network: `hopp-vpc`
- Direction: `Ingress`
- Action: `Allow`
- Targets: `All instances in the network`
- Source filter: `IPv6 ranges`
- Source IPv6 ranges: `::/0`
- Protocols / ports: `tcp:80,443`

### SSH

- Existing rule retained: `hopp-vpc-allow-ssh`

---

## What Was Intentionally Not Done

- The existing `default` VPC was not edited or removed.
- `finluybackend` was not touched.
- The current `hopp-prod` VM was not moved in place.

Reason:

- the safe path is to create a replacement HOPP VM inside `hopp-vpc` / `hopp-subnet`, verify it, then cut traffic over

---

## Next Step

Create a replacement HOPP VM in:

- VPC: `hopp-vpc`
- Subnet: `hopp-subnet`
- Region: `asia-southeast1`
- Zone: `asia-southeast1-c`

Then:

1. deploy the current production stack on that replacement VM
2. verify IPv4 + IPv6 reachability
3. issue the correct TLS certificate for `humansofphnompenh.com` (and `www` if required)
4. continue the final hostname/DNS cutover
