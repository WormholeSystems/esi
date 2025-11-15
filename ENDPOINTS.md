# EVE Online ESI Endpoints Implementation Checklist

**OpenAPI Specification:** [https://esi.evetech.net/meta/openapi.json?compatibility_date=2025-11-06](https://esi.evetech.net/meta/openapi.json?compatibility_date=2025-11-06)

**Last Updated:** 2025-11-14

---

## Progress Overview

- [ ] Alliance (7 endpoints)
- [ ] Assets (4 endpoints)
- [ ] Bookmarks (4 endpoints)
- [ ] Calendar (5 endpoints)
- [ ] Character (19 endpoints)
- [ ] Clones (2 endpoints)
- [ ] Contacts (13 endpoints)
- [ ] Contracts (10 endpoints)
- [ ] Corporation (29 endpoints)
- [ ] Corporation Projects (5 endpoints)
- [ ] Dogma (4 endpoints)
- [ ] Faction Warfare (10 endpoints)
- [ ] Fittings (3 endpoints)
- [ ] Fleets (11 endpoints)
- [ ] Incursions (1 endpoint)
- [ ] Industry (4 endpoints)
- [ ] Insurance (1 endpoint)
- [ ] Killmails (2 endpoints)
- [ ] Location (4 endpoints)
- [ ] Loyalty (2 endpoints)
- [ ] Mail (8 endpoints)
- [ ] Market (14 endpoints)
- [ ] Meta (1 endpoint)
- [ ] Opportunities (2 endpoints)
- [ ] Planetary Interaction (7 endpoints)
- [ ] Routes (1 endpoint)
- [ ] Search (1 endpoint)
- [ ] Skills (4 endpoints)
- [ ] Sovereignty (3 endpoints)
- [ ] Status (1 endpoint)
- [ ] Universe (38 endpoints)
- [ ] User Interface (1 endpoint)
- [ ] Wallet (5 endpoints)
- [ ] Wars (3 endpoints)

---

## Alliance

- [ ] `GET /alliances/` - List all active player alliances
- [ ] `GET /alliances/{alliance_id}/` - Get public information about an alliance
- [ ] `GET /alliances/{alliance_id}/contacts/` - Get alliance contacts (requires authentication)
- [ ] `GET /alliances/{alliance_id}/contacts/labels/` - Get alliance contact labels (requires authentication)
- [ ] `GET /alliances/{alliance_id}/corporations/` - List all corporations in an alliance
- [ ] `GET /alliances/{alliance_id}/icons/` - Get alliance icons
- [ ] `GET /alliances/names/` - Resolve a set of alliance IDs to alliance names (DEPRECATED)

## Assets

- [ ] `GET /characters/{character_id}/assets/` - Get character assets (requires authentication)
- [ ] `POST /characters/{character_id}/assets/locations/` - Get character asset locations (requires authentication)
- [ ] `POST /characters/{character_id}/assets/names/` - Get character asset names (requires authentication)
- [ ] `GET /corporations/{corporation_id}/assets/` - Get corporation assets (requires authentication)

## Bookmarks

- [ ] `GET /characters/{character_id}/bookmarks/` - List character bookmarks (requires authentication)
- [ ] `GET /characters/{character_id}/bookmarks/folders/` - List character bookmark folders (requires authentication)
- [ ] `GET /corporations/{corporation_id}/bookmarks/` - List corporation bookmarks (requires authentication)
- [ ] `GET /corporations/{corporation_id}/bookmarks/folders/` - List corporation bookmark folders (requires authentication)

## Calendar

- [ ] `GET /characters/{character_id}/calendar/` - List calendar event summaries (requires authentication)
- [ ] `GET /characters/{character_id}/calendar/{event_id}/` - Get an event (requires authentication)
- [ ] `PUT /characters/{character_id}/calendar/{event_id}/` - Respond to an event (requires authentication)
- [ ] `GET /characters/{character_id}/calendar/{event_id}/attendees/` - Get attendees (requires authentication)

## Character

- [x] `POST /characters/affiliation/` - Get character affiliations for multiple character IDs
- [x] `GET /characters/{character_id}/` - Get public information about a character
- [ ] `GET /characters/{character_id}/agents_research/` - Get agents research (requires authentication)
- [ ] `GET /characters/{character_id}/blueprints/` - Get character blueprints (requires authentication)
- [ ] `GET /characters/{character_id}/corporationhistory/` - Get corporation history
- [ ] `GET /characters/{character_id}/fatigue/` - Get jump fatigue (requires authentication)
- [ ] `GET /characters/{character_id}/medals/` - Get character medals (requires authentication)
- [ ] `GET /characters/{character_id}/notifications/` - Get character notifications (requires authentication)
- [ ] `GET /characters/{character_id}/notifications/contacts/` - Get new contact notifications (requires authentication)
- [ ] `GET /characters/{character_id}/portrait/` - Get character portraits
- [ ] `GET /characters/{character_id}/roles/` - Get character roles (requires authentication)
- [ ] `GET /characters/{character_id}/standings/` - Get character standings (requires authentication)
- [ ] `GET /characters/{character_id}/stats/` - Get character yearly stats
- [ ] `GET /characters/{character_id}/titles/` - Get character titles (requires authentication)
- [ ] `GET /characters/names/` - Resolve character IDs to names (DEPRECATED)

## Clones

- [ ] `GET /characters/{character_id}/clones/` - Get clones (requires authentication)
- [ ] `GET /characters/{character_id}/implants/` - Get active implants (requires authentication)

## Contacts

- [ ] `DELETE /characters/{character_id}/contacts/` - Delete contacts (requires authentication)
- [ ] `GET /characters/{character_id}/contacts/` - Get contacts (requires authentication)
- [ ] `POST /characters/{character_id}/contacts/` - Add contacts (requires authentication)
- [ ] `PUT /characters/{character_id}/contacts/` - Edit contacts (requires authentication)
- [ ] `GET /characters/{character_id}/contacts/labels/` - Get contact labels (requires authentication)
- [ ] `GET /corporations/{corporation_id}/contacts/` - Get corporation contacts (requires authentication)
- [ ] `GET /corporations/{corporation_id}/contacts/labels/` - Get corporation contact labels (requires authentication)
- [ ] `GET /alliances/{alliance_id}/contacts/` - Get alliance contacts (requires authentication)
- [ ] `GET /alliances/{alliance_id}/contacts/labels/` - Get alliance contact labels (requires authentication)

## Contracts

- [ ] `GET /characters/{character_id}/contracts/` - Get character contracts (requires authentication)
- [ ] `GET /characters/{character_id}/contracts/{contract_id}/bids/` - Get contract bids (requires authentication)
- [ ] `GET /characters/{character_id}/contracts/{contract_id}/items/` - Get contract items (requires authentication)
- [ ] `GET /corporations/{corporation_id}/contracts/` - Get corporation contracts (requires authentication)
- [ ] `GET /corporations/{corporation_id}/contracts/{contract_id}/bids/` - Get corporation contract bids (requires authentication)
- [ ] `GET /corporations/{corporation_id}/contracts/{contract_id}/items/` - Get corporation contract items (requires authentication)
- [ ] `GET /contracts/public/{region_id}/` - Get public contracts in a region
- [ ] `GET /contracts/public/bids/{contract_id}/` - Get public contract bids
- [ ] `GET /contracts/public/items/{contract_id}/` - Get public contract items

## Corporation

- [ ] `GET /corporations/npccorps/` - Get NPC corporations
- [ ] `GET /corporations/{corporation_id}/` - Get public corporation information
- [ ] `GET /corporations/{corporation_id}/alliancehistory/` - Get alliance history
- [ ] `GET /corporations/{corporation_id}/assets/` - Get corporation assets (requires authentication)
- [ ] `POST /corporations/{corporation_id}/assets/locations/` - Get corporation asset locations (requires authentication)
- [ ] `POST /corporations/{corporation_id}/assets/names/` - Get corporation asset names (requires authentication)
- [ ] `GET /corporations/{corporation_id}/blueprints/` - Get corporation blueprints (requires authentication)
- [ ] `GET /corporations/{corporation_id}/bookmarks/` - Get corporation bookmarks (requires authentication)
- [ ] `GET /corporations/{corporation_id}/bookmarks/folders/` - Get corporation bookmark folders (requires authentication)
- [ ] `GET /corporations/{corporation_id}/containers/logs/` - Get corporation container logs (requires authentication)
- [ ] `GET /corporations/{corporation_id}/contracts/` - Get corporation contracts (requires authentication)
- [ ] `GET /corporations/{corporation_id}/customs_offices/` - Get corporation customs offices (requires authentication)
- [ ] `GET /corporations/{corporation_id}/divisions/` - Get corporation divisions (requires authentication)
- [ ] `GET /corporations/{corporation_id}/facilities/` - Get corporation facilities (requires authentication)
- [ ] `GET /corporations/{corporation_id}/fw/stats/` - Get corporation FW stats
- [ ] `GET /corporations/{corporation_id}/icons/` - Get corporation icons
- [ ] `GET /corporations/{corporation_id}/industry/jobs/` - Get corporation industry jobs (requires authentication)
- [ ] `GET /corporations/{corporation_id}/killmails/recent/` - Get recent corporation killmails (requires authentication)
- [ ] `GET /corporations/{corporation_id}/medals/` - Get corporation medals
- [ ] `GET /corporations/{corporation_id}/medals/issued/` - Get issued corporation medals (requires authentication)
- [ ] `GET /corporations/{corporation_id}/members/` - Get corporation members (requires authentication)
- [ ] `GET /corporations/{corporation_id}/members/limit/` - Get corporation member limit (requires authentication)
- [ ] `GET /corporations/{corporation_id}/members/titles/` - Get corporation member titles (requires authentication)
- [ ] `GET /corporations/{corporation_id}/membertracking/` - Get member tracking (requires authentication)
- [ ] `GET /corporations/{corporation_id}/orders/` - Get corporation orders (requires authentication)
- [ ] `GET /corporations/{corporation_id}/orders/history/` - Get corporation order history (requires authentication)
- [ ] `GET /corporations/{corporation_id}/roles/` - Get corporation member roles (requires authentication)
- [ ] `GET /corporations/{corporation_id}/roles/history/` - Get corporation role history (requires authentication)
- [ ] `GET /corporations/{corporation_id}/shareholders/` - Get corporation shareholders (requires authentication)
- [ ] `GET /corporations/{corporation_id}/standings/` - Get corporation standings (requires authentication)
- [ ] `GET /corporations/{corporation_id}/starbases/` - Get corporation starbases (requires authentication)
- [ ] `GET /corporations/{corporation_id}/starbases/{starbase_id}/` - Get starbase detail (requires authentication)
- [ ] `GET /corporations/{corporation_id}/structures/` - Get corporation structures (requires authentication)
- [ ] `GET /corporations/{corporation_id}/titles/` - Get corporation titles (requires authentication)
- [ ] `GET /corporations/{corporation_id}/wallets/` - Get corporation wallets (requires authentication)
- [ ] `GET /corporations/{corporation_id}/wallets/{division}/journal/` - Get corporation wallet journal (requires authentication)
- [ ] `GET /corporations/{corporation_id}/wallets/{division}/transactions/` - Get corporation wallet transactions (requires authentication)

## Corporation Projects

- [ ] `GET /corporations/{corporation_id}/projects/` - Get corporation projects (requires authentication)
- [ ] `GET /corporations/{corporation_id}/projects/{project_id}/` - Get corporation project (requires authentication)

## Dogma

- [ ] `GET /dogma/attributes/` - Get attributes
- [ ] `GET /dogma/attributes/{attribute_id}/` - Get attribute information
- [ ] `GET /dogma/effects/` - Get effects
- [ ] `GET /dogma/effects/{effect_id}/` - Get effect information

## Faction Warfare

- [ ] `GET /fw/leaderboards/` - Get faction warfare leaderboards
- [ ] `GET /fw/leaderboards/characters/` - Get character faction warfare leaderboards
- [ ] `GET /fw/leaderboards/corporations/` - Get corporation faction warfare leaderboards
- [ ] `GET /fw/stats/` - Get faction warfare statistics
- [ ] `GET /fw/systems/` - Get faction warfare systems
- [ ] `GET /fw/wars/` - Get faction warfare wars
- [ ] `GET /characters/{character_id}/fw/stats/` - Get character FW stats (requires authentication)
- [ ] `GET /corporations/{corporation_id}/fw/stats/` - Get corporation FW stats

## Fittings

- [ ] `DELETE /characters/{character_id}/fittings/{fitting_id}/` - Delete fitting (requires authentication)
- [ ] `GET /characters/{character_id}/fittings/` - Get fittings (requires authentication)
- [ ] `POST /characters/{character_id}/fittings/` - Create fitting (requires authentication)

## Fleets

- [ ] `DELETE /characters/{character_id}/fleet/members/{member_id}/` - Kick fleet member (requires authentication)
- [ ] `DELETE /characters/{character_id}/fleet/squads/{squad_id}/` - Delete fleet squad (requires authentication)
- [ ] `DELETE /characters/{character_id}/fleet/wings/{wing_id}/` - Delete fleet wing (requires authentication)
- [ ] `GET /characters/{character_id}/fleet/` - Get character fleet info (requires authentication)
- [ ] `GET /fleets/{fleet_id}/` - Get fleet information (requires authentication)
- [ ] `GET /fleets/{fleet_id}/members/` - Get fleet members (requires authentication)
- [ ] `GET /fleets/{fleet_id}/wings/` - Get fleet wings (requires authentication)
- [ ] `POST /characters/{character_id}/fleet/wings/` - Create fleet wing (requires authentication)
- [ ] `POST /characters/{character_id}/fleet/wings/{wing_id}/squads/` - Create fleet squad (requires authentication)
- [ ] `PUT /fleets/{fleet_id}/` - Update fleet settings (requires authentication)
- [ ] `PUT /fleets/{fleet_id}/members/{member_id}/` - Move fleet member (requires authentication)
- [ ] `PUT /fleets/{fleet_id}/squads/{squad_id}/` - Rename fleet squad (requires authentication)
- [ ] `PUT /fleets/{fleet_id}/wings/{wing_id}/` - Rename fleet wing (requires authentication)

## Incursions

- [ ] `GET /incursions/` - List incursions

## Industry

- [ ] `GET /characters/{character_id}/industry/jobs/` - Get character industry jobs (requires authentication)
- [ ] `GET /characters/{character_id}/mining/` - Get character mining ledger (requires authentication)
- [ ] `GET /corporations/{corporation_id}/industry/jobs/` - Get corporation industry jobs (requires authentication)
- [ ] `GET /corporations/{corporation_id}/mining/extractions/` - Get corporation mining extractions (requires authentication)
- [ ] `GET /corporations/{corporation_id}/mining/observers/` - Get corporation mining observers (requires authentication)
- [ ] `GET /corporations/{corporation_id}/mining/observers/{observer_id}/` - Get mining observer details (requires authentication)
- [ ] `GET /industry/facilities/` - Get industry facilities
- [ ] `GET /industry/systems/` - Get solar system cost indices

## Insurance

- [ ] `GET /insurance/prices/` - Get insurance prices

## Killmails

- [ ] `GET /characters/{character_id}/killmails/recent/` - Get character recent killmails (requires authentication)
- [ ] `GET /corporations/{corporation_id}/killmails/recent/` - Get corporation recent killmails (requires authentication)
- [ ] `GET /killmails/{killmail_id}/{killmail_hash}/` - Get a single killmail

## Location

- [ ] `GET /characters/{character_id}/location/` - Get character location (requires authentication)
- [ ] `GET /characters/{character_id}/online/` - Get character online status (requires authentication)
- [ ] `GET /characters/{character_id}/ship/` - Get current ship (requires authentication)

## Loyalty

- [ ] `GET /characters/{character_id}/loyalty/points/` - Get loyalty points (requires authentication)
- [ ] `GET /loyalty/stores/{corporation_id}/offers/` - Get loyalty store offers

## Mail

- [ ] `DELETE /characters/{character_id}/mail/labels/{label_id}/` - Delete mail label (requires authentication)
- [ ] `DELETE /characters/{character_id}/mail/{mail_id}/` - Delete mail (requires authentication)
- [ ] `GET /characters/{character_id}/mail/` - Get mail (requires authentication)
- [ ] `GET /characters/{character_id}/mail/labels/` - Get mail labels (requires authentication)
- [ ] `GET /characters/{character_id}/mail/lists/` - Get mailing lists (requires authentication)
- [ ] `GET /characters/{character_id}/mail/{mail_id}/` - Get mail details (requires authentication)
- [ ] `POST /characters/{character_id}/mail/` - Send mail (requires authentication)
- [ ] `POST /characters/{character_id}/mail/labels/` - Create mail label (requires authentication)
- [ ] `PUT /characters/{character_id}/mail/{mail_id}/` - Update mail metadata (requires authentication)

## Market

- [ ] `GET /characters/{character_id}/orders/` - Get character orders (requires authentication)
- [ ] `GET /characters/{character_id}/orders/history/` - Get character order history (requires authentication)
- [ ] `GET /corporations/{corporation_id}/orders/` - Get corporation orders (requires authentication)
- [ ] `GET /corporations/{corporation_id}/orders/history/` - Get corporation order history (requires authentication)
- [ ] `GET /markets/groups/` - Get market groups
- [ ] `GET /markets/groups/{market_group_id}/` - Get market group information
- [ ] `GET /markets/prices/` - Get market prices
- [ ] `GET /markets/structures/{structure_id}/` - Get structure markets (requires authentication)
- [ ] `GET /markets/{region_id}/history/` - Get historical market statistics
- [ ] `GET /markets/{region_id}/orders/` - Get market orders in a region
- [ ] `GET /markets/{region_id}/types/` - Get types traded in a region

## Meta

- [ ] `GET /meta/versions/` - Get versions

## Opportunities

- [ ] `GET /characters/{character_id}/opportunities/` - Get character opportunities (requires authentication)
- [ ] `GET /opportunities/groups/` - Get opportunities groups
- [ ] `GET /opportunities/groups/{group_id}/` - Get opportunities group
- [ ] `GET /opportunities/tasks/` - Get opportunities tasks
- [ ] `GET /opportunities/tasks/{task_id}/` - Get opportunities task

## Planetary Interaction

- [ ] `GET /characters/{character_id}/planets/` - Get colonies (requires authentication)
- [ ] `GET /characters/{character_id}/planets/{planet_id}/` - Get colony layout (requires authentication)
- [ ] `GET /corporations/{corporation_id}/customs_offices/` - Get corporation customs offices (requires authentication)
- [ ] `GET /universe/schematics/{schematic_id}/` - Get schematic information

## Routes

- [ ] `GET /route/{origin}/{destination}/` - Get route between two solar systems

## Search

- [ ] `GET /characters/{character_id}/search/` - Search (requires authentication)
- [ ] `GET /search/` - Search on a string

## Skills

- [ ] `GET /characters/{character_id}/attributes/` - Get character attributes (requires authentication)
- [ ] `GET /characters/{character_id}/skillqueue/` - Get character skill queue (requires authentication)
- [ ] `GET /characters/{character_id}/skills/` - Get character skills (requires authentication)

## Sovereignty

- [ ] `GET /sovereignty/campaigns/` - Get sovereignty campaigns
- [ ] `GET /sovereignty/map/` - Get sovereignty map
- [ ] `GET /sovereignty/structures/` - Get sovereignty structures

## Status

- [ ] `GET /status/` - Get server status

## Universe

- [ ] `GET /universe/ancestries/` - Get ancestries
- [ ] `GET /universe/asteroid_belts/{asteroid_belt_id}/` - Get asteroid belt information
- [ ] `GET /universe/bloodlines/` - Get bloodlines
- [ ] `GET /universe/categories/` - Get item categories
- [ ] `GET /universe/categories/{category_id}/` - Get item category information
- [ ] `GET /universe/constellations/` - Get constellations
- [ ] `GET /universe/constellations/{constellation_id}/` - Get constellation information
- [ ] `GET /universe/factions/` - Get factions
- [ ] `GET /universe/graphics/` - Get graphics
- [ ] `GET /universe/graphics/{graphic_id}/` - Get graphic information
- [ ] `GET /universe/groups/` - Get item groups
- [ ] `GET /universe/groups/{group_id}/` - Get item group information
- [ ] `POST /universe/ids/` - Resolve IDs to names
- [ ] `POST /universe/names/` - Resolve names to IDs
- [ ] `GET /universe/moons/{moon_id}/` - Get moon information
- [ ] `GET /universe/planets/{planet_id}/` - Get planet information
- [ ] `GET /universe/races/` - Get races
- [ ] `GET /universe/regions/` - Get regions
- [ ] `GET /universe/regions/{region_id}/` - Get region information
- [ ] `GET /universe/schematics/{schematic_id}/` - Get schematic information
- [ ] `GET /universe/stargates/{stargate_id}/` - Get stargate information
- [ ] `GET /universe/stars/{star_id}/` - Get star information
- [ ] `GET /universe/stations/{station_id}/` - Get station information
- [ ] `GET /universe/structures/` - List all public structures
- [ ] `GET /universe/structures/{structure_id}/` - Get structure information
- [ ] `GET /universe/system_jumps/` - Get system jumps
- [ ] `GET /universe/system_kills/` - Get system kills
- [ ] `GET /universe/systems/` - Get solar systems
- [ ] `GET /universe/systems/{system_id}/` - Get solar system information
- [ ] `GET /universe/types/` - Get types
- [ ] `GET /universe/types/{type_id}/` - Get type information

## User Interface

- [ ] `POST /ui/autopilot/waypoint/` - Set autopilot waypoint (requires authentication)
- [ ] `POST /ui/openwindow/contract/` - Open contract window (requires authentication)
- [ ] `POST /ui/openwindow/information/` - Open information window (requires authentication)
- [ ] `POST /ui/openwindow/marketdetails/` - Open market details window (requires authentication)
- [ ] `POST /ui/openwindow/newmail/` - Open new mail window (requires authentication)

## Wallet

- [ ] `GET /characters/{character_id}/wallet/` - Get character wallet balance (requires authentication)
- [ ] `GET /characters/{character_id}/wallet/journal/` - Get character wallet journal (requires authentication)
- [ ] `GET /characters/{character_id}/wallet/transactions/` - Get character wallet transactions (requires authentication)
- [ ] `GET /corporations/{corporation_id}/wallets/` - Get corporation wallets (requires authentication)
- [ ] `GET /corporations/{corporation_id}/wallets/{division}/journal/` - Get corporation wallet journal (requires authentication)
- [ ] `GET /corporations/{corporation_id}/wallets/{division}/transactions/` - Get corporation wallet transactions (requires authentication)

## Wars

- [ ] `GET /wars/` - List wars
- [ ] `GET /wars/{war_id}/` - Get war information
- [ ] `GET /wars/{war_id}/killmails/` - Get war killmails

---

## Implementation Notes

### Legend
- [x] Implemented
- [ ] Not implemented
- 🔒 Requires authentication
- 🔄 Paginated
- ⚡ Cached

### Priority Categories

**High Priority** (Core functionality):
- Character
- Corporation
- Assets
- Market
- Universe
- Skills
- Wallet

**Medium Priority** (Common use cases):
- Contacts
- Contracts
- Industry
- Killmails
- Location
- Mail

**Low Priority** (Specialized features):
- Faction Warfare
- Fleets
- Sovereignty
- Wars
- Opportunities

### Next Steps

1. Implement Request classes for each endpoint
2. Create corresponding DTO classes for responses
3. Add Feature classes to organize related endpoints
4. Write tests for each endpoint
5. Document usage examples

### Resources

- [ESI Documentation](https://docs.esi.evetech.net/)
- [OpenAPI Spec](https://esi.evetech.net/meta/openapi.json?compatibility_date=2025-11-06)
- [ESI Community on Discord](https://discord.gg/eveonline)

