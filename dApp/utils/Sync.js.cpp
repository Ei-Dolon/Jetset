// @file Sync.js
/// @namespace Sync
/// Module for loading and storing data
namespace Sync {

/// Loads an resource
/// @param id the GUID of the resource
/// @param success callback to be executed with the data on suceess
/// @param error callback to be executed with error description in case of failure
/// Loads an resource
undefined load(String id, Function success, Function error);
}

namespace Sync {

/// Store an resource
/// @param id the GUID of the resource
/// @param data the resource
/// @param success callback to be executed when the store operation has completed successfully
/// @param error callback to be executed with error description in case of failure
/// Store an resource
undefined store(String id, Object data, Function success, Function error);
}
