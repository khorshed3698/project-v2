function generateAttachmentKey(org_status_id, ownership_status_id, prefix = "br") {
    let organization_key = "";
    let ownership_key = "";
  
    switch (parseInt(org_status_id)) {
      case 1:
        organization_key = "join";
        break;
      case 2:
        organization_key = "fore";
        break;
      case 3:
        organization_key = "loca";
        break;
      default:
        console.warn("Unknown organization status ID");
    }
  
    switch (parseInt(ownership_status_id)) {
      case 1:
        ownership_key = "comp";
        break;
      case 2:
        ownership_key = "part";
        break;
      case 3:
        ownership_key = "prop";
        break;
      default:
        console.warn("Unknown ownership status ID");
    }
  
    return prefix + "_" + ownership_key + "_" + organization_key;
}